<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once __DIR__ . '/support/AjaxIntegrationTestCase.php';

class viewAjaxSqlInjectionTest extends AjaxIntegrationTestCase {

	public function testValidComponentTypeUpdatesTheOrder(): void {
		$this->givenAdminIsLoggedIn();
		$component = $this->givenAViewDataWith('eqLogic', 5);

		$response = $this->whenSetComponentOrderIsCalledWith([[
			'viewZone_id' => $component->viewZoneId,
			'id'          => $component->linkId,
			'viewOrder'   => 42,
			'type'        => 'eqLogic',
		]]);

		$this->thenResponseIsJsonSuccess($response);
		$this->thenViewDataOrderIs($component->viewDataId, 42);
	}

	public function testSqlInjectionPayloadInTypeFieldIsRejected(): void {
		$this->givenAdminIsLoggedIn();
		$component = $this->givenAViewDataWith('eqLogic', 5);

		$response = $this->whenSetComponentOrderIsCalledWith([[
			'viewZone_id' => $component->viewZoneId,
			'id'          => $component->linkId,
			'viewOrder'   => 999,
			'type'        => 'eqLogic"; DROP TABLE viewData; --',
		]]);

		$this->thenResponseIsJsonSuccess($response);
		$this->thenTableStillExists('viewData');
		$this->thenViewDataOrderIs($component->viewDataId, $component->initialOrder);
	}

	public function testUnionBasedSqlInjectionIsRejected(): void {
		$this->givenAdminIsLoggedIn();
		$component = $this->givenAViewDataWith('eqLogic', 5);

		$response = $this->whenSetComponentOrderIsCalledWith([[
			'viewZone_id' => $component->viewZoneId,
			'id'          => $component->linkId,
			'viewOrder'   => 999,
			'type'        => 'eqLogic" UNION SELECT password FROM user --',
		]]);

		$this->thenResponseIsJsonSuccess($response);
		$this->thenViewDataOrderIs($component->viewDataId, $component->initialOrder);
	}

	public function testUnknownComponentTypeIsRejected(): void {
		$this->givenAdminIsLoggedIn();
		// Fixture type matches the injected type so the vulnerable WHERE would hit this row.
		$component = $this->givenAViewDataWith('arbitrary_string', 5);

		$response = $this->whenSetComponentOrderIsCalledWith([[
			'viewZone_id' => $component->viewZoneId,
			'id'          => $component->linkId,
			'viewOrder'   => 999,
			'type'        => 'arbitrary_string',
		]]);

		$this->thenResponseIsJsonSuccess($response);
		$this->thenViewDataOrderIs($component->viewDataId, $component->initialOrder);
	}

	public function testMissingComponentTypeIsRejected(): void {
		$this->givenAdminIsLoggedIn();
		// Fixture type is empty so the vulnerable code — which concatenates a
		// null into type="" — would match this row and bump its order.
		$component = $this->givenAViewDataWith('', 5);

		$response = $this->whenSetComponentOrderIsCalledWith([[
			'viewZone_id' => $component->viewZoneId,
			'id'          => $component->linkId,
			'viewOrder'   => 999,
		]]);

		$this->thenResponseIsJsonSuccess($response);
		$this->thenViewDataOrderIs($component->viewDataId, $component->initialOrder);
	}
}
