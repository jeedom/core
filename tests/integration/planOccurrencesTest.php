<?php

require_once __DIR__ . '/support/AjaxIntegrationTestCase.php';

final class planOccurrencesTest extends AjaxIntegrationTestCase {
	public static function targetTypes(): array {
		return [['eqLogic'], ['cmd'], ['scenario']];
	}

	/** @dataProvider targetTypes */
	public function testLegacyAddAndSaveRespectSeparateDesignItems(string $type): void {
		$this->givenAdminIsLoggedIn();
		$design = new planHeader();
		$design->setName('test_occurrences_' . bin2hex(random_bytes(4)));
		$design->save();
		$this->registerCleanup(static fn() => $design->remove());

		// Seule la disposition est testée : aucune cible réelle n'est nécessaire.
		$target = ['planHeader_id' => $design->getId(), 'link_type' => $type, 'link_id' => 987654321];
		$create = ['action' => 'create', 'plan' => json_encode($target)];
		$this->thenResponseIsJsonSuccess($this->callAjaxEndpoint('plan.ajax.php', $create));
		$first = $design->getPlan()[0];
		display::replaceForPlan($first->getId(), $type, $target['link_id'], ['template' => 'old']);
		$this->thenResponseIsJsonSuccess($this->callAjaxEndpoint('plan.ajax.php', $create));
		$this->assertCount(1, $design->getPlan());
		$this->assertEmpty(display::byPlanIds([$first->getId()]));

		$first = $design->getPlan()[0];
		display::replaceForPlan($first->getId(), $type, $target['link_id'], ['template' => 'compact']);
		$second = new plan();
		utils::a2o($second, $target);
		$second->setPosition('left', 120);
		$second->save();
		display::replaceForPlan($second->getId(), $type, $target['link_id'], ['template' => 'large']);

		foreach ([$create, ['action' => 'save', 'plans' => json_encode([$target])]] as $request) {
			$result = $this->callAjaxEndpoint('plan.ajax.php', $request);
			$this->assertSame('error', $result['json']['state']);
			$this->assertStringContainsString('Core UI', $result['json']['result']);
		}
		$update = $target + ['id' => $first->getId(), 'position' => ['left' => 45]];
		$this->thenResponseIsJsonSuccess($this->callAjaxEndpoint('plan.ajax.php', ['action' => 'save', 'plans' => json_encode([$update])]));

		$copy = $design->copy($design->getName() . '_copy');
		$this->registerCleanup(static fn() => $copy->remove());
		foreach ([$design, $copy] as $header) {
			$items = $header->getPlan();
			$this->assertCount(2, $items);
			$this->assertEquals([45, 120], [$items[0]->getPosition('left'), $items[1]->getPosition('left')]);
			$settings = display::byPlanIds([$items[0]->getId(), $items[1]->getId()]);
			$this->assertSame('compact', $settings[$items[0]->getId()]->getTemplate());
			$this->assertSame('large', $settings[$items[1]->getId()]->getTemplate());
		}
	}
}
