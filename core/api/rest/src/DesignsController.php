<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Designs (planHeader) et éléments posés (plan). Seuls la cible, la position et le texte
 * viennent de plan ; le widget et la largeur sont dans display (context = design).
 * Droits repris de plan.ajax.php : lecture avec le droit r, écriture admin et droit w.
 */
final class DesignsController
{
    private const TYPES = ['eqLogic', 'cmd', 'scenario', 'summary', 'plan', 'text'];
    private const DESIGN_FIELDS = ['name', 'icon', 'order', 'width', 'height', 'backgroundColor', 'backgroundTransparent'];
    private const IMAGES = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif'];
    private const MAX_IMAGE = 5000000;
    private const MAX_ITEMS = 1000;

    public function list(Request $request, Response $response): Response
    {
        $user = $request->getAttribute('user');
        $items = [];
        foreach ((array) \planHeader::all() as $design) {
            if ($design->hasRight('r', $user)) {
                $items[] = self::summary($design);
            }
        }
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        return Json::respond($response, self::toArray(self::find($request, $args['id'], 'r')));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = self::designBody(Json::body($request));
        if (!array_key_exists('name', $body)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Ce champ est obligatoire.');
        }
        $design = new \planHeader();
        self::applyDesign($design, $body);
        return Json::respond($response, self::toArray($design), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        $body = self::designBody(Json::body($request));
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Au moins un champ est attendu.');
        }
        self::applyDesign($design, $body);
        return Json::respond($response, self::toArray($design));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        foreach ((array) $design->getPlan() as $plan) {
            $plan->remove();
        }
        self::removeImage($design);
        $design->remove();
        return $response->withStatus(204);
    }

    public function copy(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        $body = Json::body($request);
        if (array_diff(array_keys($body), ['name']) !== []) {
            throw ApiError::field(422, 'validation_error', (string) array_key_first(array_diff_key($body, ['name' => 1])), 'Champ inconnu.');
        }
        $name = self::name($body['name'] ?? null);
        $displays = \display::byPlanIds(array_map(static fn ($plan) => (int) $plan->getId(), (array) $design->getPlan()));
        $sources = (array) $design->getPlan();
        try {
            $copy = $design->copy($name);
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
        // planHeader::copy() recrée les éléments dans le même ordre ; on y rattache leurs réglages.
        foreach ((array) $copy->getPlan() as $index => $plan) {
            $source = $sources[$index] ?? null;
            $row = $source === null ? null : ($displays[(int) $source->getId()] ?? null);
            if ($row !== null) {
                \display::replaceForPlan((int) $plan->getId(), $row->getTargetType(), $row->getTargetId(), [
                    'template' => $row->getTemplate(), 'template_parameters' => $row->getTemplateParameters(),
                ]);
            }
        }
        return Json::respond($response, self::toArray(\planHeader::byId($copy->getId())), 201);
    }

    public function background(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'r');
        $path = self::imagePath($design);
        if ($path === null || !is_file($path)) {
            throw new ApiError(404, 'design_not_found', 'Ce design n\'a pas d\'image de fond.');
        }
        $type = array_search($design->getImage('type'), self::IMAGES, true) ?: 'image/jpeg';
        $response->getBody()->write((string) file_get_contents($path));
        return $response->withHeader('Content-Type', $type);
    }

    public function replaceBackground(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        $stream = $request->getBody();
        if ($stream->isSeekable()) {
            $stream->rewind();
        }
        $bytes = $stream->read(self::MAX_IMAGE + 1);
        while (strlen($bytes) <= self::MAX_IMAGE && !$stream->eof()) {
            $chunk = $stream->read(self::MAX_IMAGE + 1 - strlen($bytes));
            if ($chunk === '') {
                break;
            }
            $bytes .= $chunk;
        }
        if (strlen($bytes) > self::MAX_IMAGE) {
            throw new ApiError(413, 'validation_error', 'Le fichier est trop gros (5 Mo au plus).');
        }
        // Le type est lu dans le contenu, jamais dans l'en-tête ni le nom de fichier.
        $info = $bytes === '' ? false : @getimagesizefromstring($bytes);
        $extension = is_array($info) ? (self::IMAGES[$info['mime']] ?? null) : null;
        if ($extension === null || !isset(self::IMAGES[strtolower(trim(explode(';', $request->getHeaderLine('Content-Type'))[0]))])) {
            throw new ApiError(415, 'validation_error', 'Une image PNG, JPEG ou GIF est attendue.');
        }
        self::removeImage($design);
        $design->setImage('type', $extension);
        $design->setImage('size', [$info[0], $info[1]]);
        $design->setImage('sha512', hash('sha512', $bytes));
        if (file_put_contents(self::imagePath($design), $bytes) === false) {
            throw new ApiError(500, 'internal_error', 'Impossible d\'enregistrer l\'image.');
        }
        $design->save();
        return Json::respond($response, self::toArray($design));
    }

    public function deleteBackground(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        self::removeImage($design);
        $design->save();
        return $response->withStatus(204);
    }

    public function createItem(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        $body = Json::body($request, 1048576, preserveObjects: true);
        self::checkFields($body, ['type', 'target_id', 'position', 'text', 'display'], '');
        $type = $body['type'] ?? null;
        if (!in_array($type, self::TYPES, true)) {
            throw ApiError::field(422, 'validation_error', 'type', 'Type d\'élément non pris en charge.');
        }
        if (!array_key_exists('position', $body)) {
            throw ApiError::field(422, 'validation_error', 'position', 'Ce champ est obligatoire.');
        }
        $plan = new \plan();
        $plan->setPlanHeader_id($design->getId());
        $plan->setLink_type($type);
        if ($type === 'text') {
            if (!array_key_exists('text', $body)) {
                throw ApiError::field(422, 'validation_error', 'text', 'Ce champ est obligatoire.');
            }
        } else {
            $plan->setLink_id(self::target($type, $body['target_id'] ?? null, $request));
        }
        $display = array_key_exists('display', $body) ? self::display($body['display']) : null;
        self::applyItem($plan, $body, $display);
        return Json::respond($response, self::item($plan, \display::byPlanIds([(int) $plan->getId()])), 201);
    }

    public function updateItem(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        $plan = self::findItem($design, $args['itemId']);
        $body = Json::body($request, 1048576, preserveObjects: true);
        self::checkFields($body, ['position', 'text', 'display'], '');
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Au moins un champ est attendu.');
        }
        if (array_key_exists('text', $body) && $plan->getLink_type() !== 'text') {
            throw ApiError::field(422, 'validation_error', 'text', 'Seul un élément texte a un texte.');
        }
        $display = array_key_exists('display', $body) ? self::display($body['display']) : null;
        self::applyItem($plan, $body, $display);
        return Json::respond($response, self::item($plan, \display::byPlanIds([(int) $plan->getId()])));
    }

    public function moveItems(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        $body = Json::body($request, 1048576, preserveObjects: true);
        self::checkFields($body, ['items'], '');
        $moves = $body['items'] ?? null;
        if (!is_array($moves) || $moves === [] || count($moves) > self::MAX_ITEMS) {
            throw ApiError::field(422, 'validation_error', 'items', 'Une liste de 1 à 1 000 éléments est attendue.');
        }
        $plans = [];
        foreach ((array) $design->getPlan() as $plan) {
            $plans[(int) $plan->getId()] = $plan;
        }
        $positions = [];
        foreach ($moves as $index => $move) {
            $field = 'items.' . $index;
            if (!$move instanceof \stdClass) {
                throw ApiError::field(422, 'validation_error', $field, 'Un objet JSON est attendu.');
            }
            $move = (array) $move;
            self::checkFields($move, ['id', 'position'], $field . '.');
            $id = $move['id'] ?? null;
            if (!is_int($id) || !isset($plans[$id]) || isset($positions[$id])) {
                throw ApiError::field(422, 'validation_error', $field . '.id', 'Élément inconnu de ce design ou en double.');
            }
            $positions[$id] = self::position($move['position'] ?? null, $field . '.position');
        }
        // Tout est validé avant la première écriture : tout ou rien.
        foreach ($positions as $id => $position) {
            $plans[$id]->setPosition('top', $position['top']);
            $plans[$id]->setPosition('left', $position['left']);
            $plans[$id]->save();
        }
        return $response->withStatus(204);
    }

    public function deleteItem(Request $request, Response $response, array $args): Response
    {
        $design = self::find($request, $args['id'], 'w');
        self::findItem($design, $args['itemId'])->remove();
        return $response->withStatus(204);
    }

    private static function find(Request $request, string $id, string $right): \planHeader
    {
        if ($right === 'w') {
            Auth::requireAdmin($request);
        }
        $design = ctype_digit($id) ? \planHeader::byId((int) $id) : null;
        if (!is_object($design)) {
            throw new ApiError(404, 'design_not_found', 'Design inconnu.');
        }
        if (!$design->hasRight($right, $request->getAttribute('user'))) {
            throw new ApiError(403, 'forbidden', 'Droit insuffisant sur ce design.');
        }
        return $design;
    }

    private static function findItem(\planHeader $design, string $id): \plan
    {
        $plan = ctype_digit($id) ? \plan::byId((int) $id) : null;
        if (!is_object($plan) || (int) $plan->getPlanHeader_id() !== (int) $design->getId()) {
            throw new ApiError(404, 'design_not_found', 'Élément inconnu dans ce design.');
        }
        return $plan;
    }

    private static function target(string $type, mixed $id, Request $request): int
    {
        if (!is_int($id) || $id < 1) {
            throw ApiError::field(422, 'validation_error', 'target_id', 'Un identifiant entier positif est attendu.');
        }
        $user = $request->getAttribute('user');
        $target = match ($type) {
            'eqLogic' => \eqLogic::byId($id),
            'cmd' => \cmd::byId($id),
            'scenario' => \scenario::byId($id),
            'summary' => \jeeObject::byId($id),
            'plan' => \planHeader::byId($id),
        };
        if (!is_object($target) || ($type === 'plan' && !$target->hasRight('r', $user))) {
            throw ApiError::field(422, 'validation_error', 'target_id', 'Cible inconnue.');
        }
        return $id;
    }

    private static function applyItem(\plan $plan, array $body, ?array $display): void
    {
        if (array_key_exists('position', $body)) {
            $position = self::position($body['position'], 'position');
            $plan->setPosition('top', $position['top']);
            $plan->setPosition('left', $position['left']);
        }
        if (array_key_exists('text', $body)) {
            if (!is_string($body['text']) || mb_strlen($body['text']) > 1000) {
                throw ApiError::field(422, 'validation_error', 'text', 'Un texte de 1 000 caractères au plus est attendu.');
            }
            $plan->setDisplay('text', $body['text']);
        }
        $plan->save();
        if (array_key_exists('display', $body)) {
            \display::replaceForPlan((int) $plan->getId(), (string) $plan->getLink_type(), (int) $plan->getLink_id(), $display);
        }
    }

    /** @return array{top: int, left: int} */
    private static function position(mixed $value, string $field): array
    {
        $value = $value instanceof \stdClass ? (array) $value : $value;
        if (!is_array($value) || array_keys($value) != ['top', 'left'] && array_keys($value) != ['left', 'top']) {
            throw ApiError::field(422, 'validation_error', $field, 'Un objet {top, left} est attendu.');
        }
        foreach (['top', 'left'] as $key) {
            if (!is_int($value[$key]) || $value[$key] < 0) {
                throw ApiError::field(422, 'validation_error', $field . '.' . $key, 'Un entier positif ou nul est attendu.');
            }
        }
        return ['top' => $value['top'], 'left' => $value['left']];
    }

    private static function display(mixed $value): ?array
    {
        if (!$value instanceof \stdClass) {
            throw ApiError::field(422, 'validation_error', 'display', 'Un objet JSON est attendu.');
        }
        $data = (array) $value;
        self::checkFields($data, ['template', 'template_parameters'], 'display.');
        $template = $data['template'] ?? null;
        if ($template !== null && (!is_string($template) || trim($template) === '' || mb_strlen($template) > 127)) {
            throw ApiError::field(422, 'validation_error', 'display.template', 'Une clé de rendu de 1 à 127 caractères est attendue.');
        }
        if (array_key_exists('template_parameters', $data) && !$data['template_parameters'] instanceof \stdClass) {
            throw ApiError::field(422, 'validation_error', 'display.template_parameters', 'Un objet JSON est attendu.');
        }
        return $data;
    }

    private static function designBody(array $body): array
    {
        if ($unknown = array_diff(array_keys($body), self::DESIGN_FIELDS)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
        if (array_key_exists('name', $body)) {
            $body['name'] = self::name($body['name']);
        }
        if (array_key_exists('icon', $body) && !is_string($body['icon'])) {
            throw ApiError::field(422, 'validation_error', 'icon', 'Un texte est attendu.');
        }
        if (array_key_exists('order', $body) && !is_int($body['order'])) {
            throw ApiError::field(422, 'validation_error', 'order', 'Un entier est attendu.');
        }
        foreach (['width', 'height'] as $field) {
            if (array_key_exists($field, $body) && (!is_int($body[$field]) || $body[$field] < 1)) {
                throw ApiError::field(422, 'validation_error', $field, 'Un entier positif est attendu.');
            }
        }
        if (array_key_exists('backgroundColor', $body) && (!is_string($body['backgroundColor']) || !preg_match('/^#[0-9a-fA-F]{6}$/', $body['backgroundColor']))) {
            throw ApiError::field(422, 'validation_error', 'backgroundColor', 'Une couleur #rrggbb est attendue.');
        }
        if (array_key_exists('backgroundTransparent', $body) && !is_bool($body['backgroundTransparent'])) {
            throw ApiError::field(422, 'validation_error', 'backgroundTransparent', 'Un booléen est attendu.');
        }
        return $body;
    }

    private static function name(mixed $name): string
    {
        if (!is_string($name) || trim($name) === '' || mb_strlen(trim($name)) > 127) {
            throw ApiError::field(422, 'validation_error', 'name', 'Un texte non vide de 127 caractères au plus est attendu.');
        }
        return trim($name);
    }

    private static function applyDesign(\planHeader $design, array $body): void
    {
        $map = ['icon' => 'icon', 'width' => 'desktopSizeX', 'height' => 'desktopSizeY', 'backgroundColor' => 'backgroundColor'];
        foreach ($map as $field => $key) {
            if (array_key_exists($field, $body)) {
                $design->setConfiguration($key, $body[$field]);
            }
        }
        if (array_key_exists('backgroundTransparent', $body)) {
            $design->setConfiguration('backgroundTransparent', $body['backgroundTransparent'] ? 1 : 0);
        }
        if (array_key_exists('name', $body)) {
            $design->setName($body['name']);
        }
        if (array_key_exists('order', $body)) {
            $design->setOrder($body['order']);
        }
        try {
            $design->save();
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    private static function checkFields(array $data, array $allowed, string $prefix): void
    {
        $unknown = array_diff(array_keys($data), $allowed);
        if ($unknown !== []) {
            throw ApiError::field(422, 'validation_error', $prefix . reset($unknown), 'Champ inconnu ou non modifiable.');
        }
    }

    private static function imagePath(\planHeader $design): ?string
    {
        if ($design->getImage('sha512') == '' || !in_array($design->getImage('type'), self::IMAGES, true)) {
            return null;
        }
        return __DIR__ . '/../../../../data/plan/planHeader' . (int) $design->getId() . '-'
            . basename((string) $design->getImage('sha512')) . '.' . $design->getImage('type');
    }

    private static function removeImage(\planHeader $design): void
    {
        $path = self::imagePath($design);
        if ($path !== null && is_file($path)) {
            unlink($path);
        }
        $design->setImage('sha512', '');
    }

    private static function summary(\planHeader $design): array
    {
        return [
            'id' => (int) $design->getId(),
            'name' => (string) $design->getName(),
            'icon' => (string) $design->getConfiguration('icon', ''),
            'order' => (int) $design->getOrder(),
        ];
    }

    private static function toArray(\planHeader $design): array
    {
        $plans = (array) $design->getPlan();
        $displays = \display::byPlanIds(array_map(static fn ($plan) => (int) $plan->getId(), $plans));
        $path = self::imagePath($design);
        return self::summary($design) + [
            'width' => max(1, (int) $design->getConfiguration('desktopSizeX', 500)),
            'height' => max(1, (int) $design->getConfiguration('desktopSizeY', 500)),
            'backgroundColor' => (string) $design->getConfiguration('backgroundColor', '#ffffff'),
            'backgroundTransparent' => (bool) $design->getConfiguration('backgroundTransparent', 1),
            'hasBackground' => $path !== null && is_file($path),
            'items' => array_map(static fn ($plan) => self::item($plan, $displays), $plans),
        ];
    }

    /** @param array<int, \display> $displays */
    private static function item(\plan $plan, array $displays): array
    {
        $type = (string) $plan->getLink_type();
        $item = [
            'id' => (int) $plan->getId(),
            'type' => $type,
            'target_id' => $type === 'text' ? null : (int) $plan->getLink_id(),
            'position' => ['top' => (int) $plan->getPosition('top', 0), 'left' => (int) $plan->getPosition('left', 0)],
            'supported' => in_array($type, self::TYPES, true),
        ];
        if ($type === 'text') {
            $item['text'] = (string) $plan->getDisplay('text', '');
        }
        $row = $displays[(int) $plan->getId()] ?? null;
        if ($row !== null) {
            $item['display'] = ['template' => $row->getTemplate(), 'template_parameters' => $row->getTemplateParameters()];
        }
        return $item;
    }
}
