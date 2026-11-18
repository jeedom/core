<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Stream;

final class ReportTemplatesController
{
    private const MAX_BODY = 1048576;

    public function list(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $query = ListQuery::from($request);
        $items = [];
        foreach (\reportTemplate::summaries() as $row) {
            if ($query->matches([$row['name']])) {
                $items[] = self::summary($row);
            }
        }
        return Json::respond($response, $query->page($items));
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::detail(self::find($args['id'])));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = self::body($request, ['name', 'layout']);
        if (!array_key_exists('name', $body)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Ce champ est obligatoire.');
        }
        $template = new \reportTemplate();
        self::apply($template, $body);
        $template->save();
        return Json::respond($response, self::detail($template), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $template = self::find($args['id']);
        $body = self::body($request, ['name', 'layout', 'revision']);
        $revision = $body['revision'] ?? null;
        if (!is_int($revision) || $revision < 1 || $revision > 4294967295) {
            throw ApiError::field(422, 'validation_error', 'revision', 'Une révision valide est attendue.');
        }
        if (!array_key_exists('name', $body) && !array_key_exists('layout', $body)) {
            throw new ApiError(422, 'validation_error', 'Le nom ou la composition doit être fourni.');
        }
        self::apply($template, $body);
        try {
            $saved = $template->save($revision);
        } catch (\OutOfBoundsException) {
            throw self::notFound();
        }
        if (!$saved) {
            throw new ApiError(409, 'report_template_revision_conflict', 'Le modèle a été modifié depuis son ouverture.');
        }
        return Json::respond($response, self::detail($template));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        try {
            self::find($args['id'])->remove();
        } catch (\OutOfBoundsException) {
            throw self::notFound();
        }
        return $response->withStatus(204);
    }

    public function render(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $template = self::find($args['id']);
        self::apply($template, self::body($request, ['layout'], allowEmpty: true));
        $pdf = self::renderPdf($template, $request);
        $response = $response->withBody(new Stream($pdf));
        return Attachment::headers($response, self::pdfName($template) . '.pdf', 'application/pdf', inline: true)
            ->withHeader('Cache-Control', 'private, no-store');
    }

    public function listReports(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $template = self::find($args['id']);
        $items = \reportTemplateArchive::list((int) $template->getId());
        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), ['items' => $items, 'total' => count($items)]);
    }

    public function uploadImage(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $id = (int) self::find($args['id'])->getId();
        try {
            $imageId = ObjectsController::withUploadedImage($request, static fn (string $path) => \reportTemplateImage::save($id, $path));
        } catch (\InvalidArgumentException $error) {
            throw ApiError::field(422, 'validation_error', 'file', $error->getMessage());
        } catch (\OutOfBoundsException) {
            throw self::notFound();
        }
        return Json::respond($response, ['imageId' => $imageId], 201);
    }

    public function image(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $id = (int) self::find($args['id'])->getId();
        $path = \reportTemplateImage::path($id, $args['imageId']);
        $info = $path === null ? false : @getimagesize($path);
        $file = $info === false ? false : @fopen($path, 'rb');
        if ($file === false) {
            throw new ApiError(404, 'report_image_not_found', 'Image du rapport introuvable.');
        }
        return $response->withBody(new Stream($file))
            ->withHeader('Content-Type', $info['mime'])
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('Cache-Control', 'private, no-store');
    }

    public function createReport(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $template = self::find($args['id']);
        $pdf = self::renderPdf($template, $request);
        try {
            $report = \reportTemplateArchive::save((int) $template->getId(), $pdf);
        } catch (\OutOfBoundsException) {
            throw self::notFound();
        } finally {
            fclose($pdf);
        }
        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), $report, 201);
    }

    public function downloadReport(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $template = self::find($args['id']);
        try {
            $handle = \reportTemplateArchive::open((int) $template->getId(), $args['reportId']);
        } catch (\OutOfBoundsException) {
            throw self::reportNotFound();
        }
        $name = self::pdfName($template) . '-' . \reportTemplateArchive::filename($args['reportId']);
        return Attachment::headers($response->withBody(new Stream($handle)), $name, 'application/pdf')
            ->withHeader('Cache-Control', 'private, no-store');
    }

    public function deleteReport(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $template = self::find($args['id']);
        try {
            \reportTemplateArchive::remove((int) $template->getId(), $args['reportId']);
        } catch (\OutOfBoundsException) {
            throw self::reportNotFound();
        }
        return $response->withStatus(204);
    }

    private static function reportNotFound(): ApiError
    {
        return new ApiError(404, 'archived_report_not_found', 'Rapport archivé introuvable.');
    }

    /** @return resource */
    private static function renderPdf(\reportTemplate $template, Request $request)
    {
        try {
            return \reportTemplateRenderer::render($template, $request->getAttribute('user'));
        } catch (\DomainException $error) {
            throw ApiError::field(422, 'report_render_invalid', 'layout', $error->getMessage());
        } catch (\RuntimeException $error) {
            throw match ($error->getCode()) {
                \reportTemplateRenderer::UNAVAILABLE => new ApiError(503, 'report_renderer_unavailable', 'Le moteur PDF est indisponible. Vérifiez les dépendances du Core.'),
                \reportTemplateRenderer::TIMEOUT => new ApiError(503, 'report_render_timeout', 'Le délai de génération du PDF est dépassé.'),
                default => $error,
            };
        }
    }

    private static function pdfName(\reportTemplate $template): string
    {
        $name = str_replace(['\\', '/'], '-', $template->getName());
        $name = trim((string) preg_replace('~[\x00-\x1f\x7f]+~', '-', $name), '. ');
        return $name !== '' ? $name : 'rapport';
    }

    private static function body(Request $request, array $fields, bool $allowEmpty = false): array
    {
        $body = Json::body($request, self::MAX_BODY, preserveObjects: true, allowEmpty: $allowEmpty);
        $unknown = array_diff(array_keys($body), $fields);
        if ($unknown !== []) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu.');
        }
        return $body;
    }

    private static function apply(\reportTemplate $template, array $body): void
    {
        if (array_key_exists('name', $body)) {
            try {
                $template->setName($body['name']);
            } catch (\InvalidArgumentException $error) {
                throw ApiError::field(422, 'validation_error', 'name', $error->getMessage());
            }
        }
        if (array_key_exists('layout', $body)) {
            try {
                $template->setLayout($body['layout']);
            } catch (\InvalidArgumentException $error) {
                throw ApiError::field(422, 'validation_error', 'layout', $error->getMessage());
            }
        }
    }

    private static function find(string $id): \reportTemplate
    {
        $template = ctype_digit($id) ? \reportTemplate::byId((int) $id) : null;
        return $template ?? throw self::notFound();
    }

    private static function notFound(): ApiError
    {
        return new ApiError(404, 'report_template_not_found', 'Modèle de rapport inconnu.');
    }

    private static function summary(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'revision' => (int) $row['revision'],
            'created_at' => str_replace(' ', 'T', $row['created_at']) . 'Z',
            'updated_at' => str_replace(' ', 'T', $row['updated_at']) . 'Z',
        ];
    }

    private static function detail(\reportTemplate $template): array
    {
        return self::summary([
            'id' => $template->getId(),
            'name' => $template->getName(),
            'revision' => $template->getRevision(),
            'created_at' => $template->getCreated_at(),
            'updated_at' => $template->getUpdated_at(),
        ]) + ['layout' => $template->getLayoutDocument()];
    }
}
