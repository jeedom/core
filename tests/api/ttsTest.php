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

require_once dirname(__DIR__, 2) . '/core/api/tts.func.php';

use PHPUnit\Framework\TestCase;

class ttsTest extends TestCase {

	// ─── tts_sanitizeLang ───────────────────────────────────────

	/**
	 * @dataProvider validLangProvider
	 */
	public function testSanitizeLangAcceptsValid(string $input, string $expected): void {
		$this->assertSame($expected, tts_sanitizeLang($input));
	}

	public function validLangProvider(): array {
		return [
			'fr_FR'  => ['fr_FR', 'fr-FR'],
			'en_US'  => ['en_US', 'en-US'],
			'de-DE'  => ['de-DE', 'de-DE'],
			'pt-BRA' => ['pt-BRA', 'pt-BRA'],
			'fr'     => ['fr', 'fr'],
		];
	}

	/**
	 * @dataProvider maliciousLangProvider
	 */
	public function testSanitizeLangRejectsInjection(string $input): void {
		$this->assertSame('fr-FR', tts_sanitizeLang($input));
	}

	public function maliciousLangProvider(): array {
		return [
			'double-quote breakout' => ['fr"; rm -rf /; echo "'],
			'subshell'              => ['$(whoami)'],
			'chained command'       => ['fr && cat /etc/passwd'],
			'semicolon'             => ['en;ls'],
			'backtick'              => ['fr`id`'],
			'path traversal'        => ['../../../etc/passwd'],
			'empty'                 => [''],
			'too short'             => ['a'],
			'suffix too long'       => ['fr-FRAN'],
		];
	}

	// ─── tts_buildEspeakCmd ────────────────────────────────────

	public function testBuildEspeakCmdStructure(): void {
		$cmd = tts_buildEspeakCmd('bonjour', 'fr+f4', '/tmp/tts/abc.mp3');
		$this->assertStringStartsWith('espeak -v', $cmd);
		$this->assertStringContainsString(escapeshellarg('bonjour'), $cmd);
		$this->assertStringContainsString(escapeshellarg('fr+f4'), $cmd);
		$this->assertStringContainsString(escapeshellarg('/tmp/tts/abc.mp3'), $cmd);
	}

	/**
	 * @dataProvider espeakInjectionProvider
	 */
	public function testBuildEspeakCmdEscapesInput(string $text, string $voice): void {
		$cmd = tts_buildEspeakCmd($text, $voice, '/tmp/tts/out.mp3');
		$this->assertStringContainsString(escapeshellarg($text), $cmd);
		$this->assertStringContainsString(escapeshellarg($voice), $cmd);
	}

	public function espeakInjectionProvider(): array {
		return [
			'text: double-quote breakout' => ['hello"; rm -rf /; echo "', 'fr+f4'],
			'text: subshell'              => ['test$(cat /etc/passwd)', 'fr+f4'],
			'text: backtick'              => ['test`id`end', 'fr+f4'],
			'voice: single-quote escape'  => ['bonjour', "fr'; rm -rf /"],
			'voice: semicolon'            => ['bonjour', 'fr;id'],
		];
	}

	/**
	 * Run the built command through the actual shell to verify arguments
	 * are interpreted literally and not as shell code.
	 */
	public function testBuildEspeakCmdShellInterpretation(): void {
		$malicious = '$(touch /tmp/pwned)';
		$cmd = tts_buildEspeakCmd($malicious, 'fr+f4', '/tmp/out.mp3');

		$echoCmd = str_replace('espeak', 'echo', explode(' --stdout', $cmd)[0]);
		$result = shell_exec($echoCmd);
		$this->assertStringContainsString($malicious, $result);
		$this->assertFileDoesNotExist('/tmp/pwned');
	}

	public function testBuildEspeakCmdUsesCustomAvconv(): void {
		$cmd = tts_buildEspeakCmd('test', 'fr', '/tmp/out.mp3', 'avconv');
		$this->assertStringContainsString('| avconv ', $cmd);
	}

	// ─── tts_buildPicoCmd ───────────────────────────────────────

	public function testBuildPicoCmdStructure(): void {
		$cmd = tts_buildPicoCmd('bonjour', 'fr_FR', '6', 'abc123', '/tmp/tts/abc123.mp3');
		$this->assertStringStartsWith('pico2wave', $cmd);
		$this->assertStringContainsString(escapeshellarg('bonjour'), $cmd);
		$this->assertStringContainsString(escapeshellarg('fr-FR'), $cmd);
		$this->assertStringContainsString('volume=6dB', $cmd);
		$this->assertStringContainsString(escapeshellarg('abc123.wav'), $cmd);
		$this->assertStringContainsString(escapeshellarg('/tmp/tts/abc123.mp3'), $cmd);
	}

	public function testBuildPicoCmdSanitizesLang(): void {
		$cmd = tts_buildPicoCmd('bonjour', 'fr;cat /etc/passwd', '6', 'abc', '/tmp/out.mp3');
		$this->assertStringContainsString(escapeshellarg('fr-FR'), $cmd);
		$this->assertStringNotContainsString('/etc/passwd', $cmd);
	}

	public function testBuildPicoCmdSanitizesVolume(): void {
		$cmd = tts_buildPicoCmd('bonjour', 'fr-FR', '6"; rm -rf /', 'abc', '/tmp/out.mp3');
		$this->assertStringContainsString('volume=6dB', $cmd);
		$this->assertStringNotContainsString('rm -rf', $cmd);
	}

	/**
	 * @dataProvider picoInjectionProvider
	 */
	public function testBuildPicoCmdEscapesInput(string $text, string $lang, string $volume): void {
		$md5 = md5($text);
		$cmd = tts_buildPicoCmd($text, $lang, $volume, $md5, '/tmp/tts/' . $md5 . '.mp3');
		$this->assertStringContainsString(escapeshellarg($text), $cmd);
	}

	public function picoInjectionProvider(): array {
		return [
			'text: subshell + backtick' => ['bonjour$(reboot)`id`', 'fr-FR', '6'],
			'lang: injection'           => ['bonjour', 'fr;cat /etc/passwd', '6'],
			'volume: injection'         => ['bonjour', 'fr-FR', '6"; rm -rf /'],
			'all malicious'             => ['$(rm -rf /)', 'en;id', '0$(reboot)'],
		];
	}

	public function testBuildPicoCmdUsesCustomAvconv(): void {
		$cmd = tts_buildPicoCmd('test', 'fr', '6', 'abc', '/tmp/out.mp3', 'avconv');
		$this->assertStringContainsString('avconv -i', $cmd);
	}
}
