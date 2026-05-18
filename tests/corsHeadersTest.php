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

use PHPUnit\Framework\TestCase;

/**
 * Regression test: `Access-Control-Allow-Credentials: true` combined with
 * `Access-Control-Allow-Origin: *` is rejected by all modern browsers, so the
 * credentials directive is dead code that misleads reviewers about the
 * intended cross-origin policy. It must not reappear in the shipped config.
 */
class corsHeadersTest extends TestCase {

	public function testHtaccessHasNoAllowCredentials() {
		$path = __DIR__ . '/../.htaccess';
		$this->assertFileExists($path);
		$content = file_get_contents($path);
		$this->assertDoesNotMatchRegularExpression(
			'/Access-Control-Allow-Credentials/i',
			$content,
			'.htaccess must not emit Access-Control-Allow-Credentials with Allow-Origin: *'
		);
	}

	public function testNginxDefaultHasNoAllowCredentials() {
		$path = __DIR__ . '/../install/nginx_default';
		$this->assertFileExists($path);
		$content = file_get_contents($path);
		$this->assertDoesNotMatchRegularExpression(
			'/Access-Control-Allow-Credentials/i',
			$content,
			'nginx_default must not emit Access-Control-Allow-Credentials with Allow-Origin: *'
		);
	}

	public function testJeeApiHasNoAllowCredentials() {
		$path = __DIR__ . '/../core/api/jeeApi.php';
		$this->assertFileExists($path);
		$content = file_get_contents($path);
		$this->assertDoesNotMatchRegularExpression(
			'/Access-Control-Allow-Credentials/i',
			$content,
			'jeeApi.php must not emit Access-Control-Allow-Credentials with Allow-Origin: *'
		);
	}
}
