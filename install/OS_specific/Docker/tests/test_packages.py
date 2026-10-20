"""Les changements de dépendances amont doivent bloquer une image incomplète."""
import importlib.util
import json
from pathlib import Path
import subprocess
import tempfile
import unittest
from unittest.mock import patch


ROOT = Path(__file__).resolve().parents[1]
SPEC = importlib.util.spec_from_file_location('image_packages', ROOT / 'check-packages.py')
PACKAGES = importlib.util.module_from_spec(SPEC)
SPEC.loader.exec_module(PACKAGES)
INSTALLER = '''apt_install() {
  apt-get -o Dpkg::Options::="--force-confdef" -y install "$@"
}
apt-get update
apt-get -f install
apt_install apache2 mariadb-client php-mysql
apt-get -y remove brltty
'''


class JeedomPackagesTests(unittest.TestCase):
    def core(self, source=INSTALLER, catalogue=None):
        directory = tempfile.TemporaryDirectory()
        self.addCleanup(directory.cleanup)
        root = Path(directory.name)
        (root / 'install').mkdir()
        (root / 'install/install.sh').write_text(source)
        (root / 'install/packages.json').write_text(json.dumps({'apt': catalogue or {}}))
        return root

    def test_apt_wrapper_options_continuations_and_comments(self):
        source = INSTALLER + 'apt install -y curl \\\n php-gd # commentaire\n'
        self.assertEqual(PACKAGES.installer_packages(source),
                         {'apache2', 'mariadb-client', 'php-mysql', 'curl', 'php-gd'})

    def test_variable_or_changed_installer_format_requires_review(self):
        for source in [INSTALLER + 'apt_install "$NEW_PACKAGES"\n', 'echo apt_install php-mysql\n']:
            with self.subTest(source=source), self.assertRaises(ValueError):
                PACKAGES.installer_packages(source)

    def test_catalogue_only_dependency_is_required_even_if_optional(self):
        core = self.core(catalogue={'new-tool': {'optional': True}, 'python-dev': {'denyDebianHigherEqual': '11'}})
        required, exceptions = PACKAGES.requirements(core)
        self.assertIn('new-tool', required)
        self.assertNotIn('python-dev', required)
        self.assertEqual(exceptions, {})
        with patch.object(PACKAGES.subprocess, 'check_output', return_value='apache2\tinstalled\t\n'), \
                self.assertRaisesRegex(ValueError, 'new-tool'):
            PACKAGES.check_packages(required)

    def test_legacy_names_require_real_providers_and_explicit_exceptions(self):
        core = self.core(INSTALLER + 'apt_install usermod visudo libav-tools libcurl3-gnutls ntpdate php-imap ntp\n')
        required, exceptions = PACKAGES.requirements(core)
        self.assertTrue({'passwd', 'sudo', 'ffmpeg', 'libcurl3t64-gnutls', 'ntpsec-ntpdate',
                         'mariadb-client-compat'} <= required)
        self.assertEqual(set(exceptions), {'php-imap', 'ntp'})
        self.assertNotIn('php-imap', required)

    def test_removed_package_is_not_counted_as_installed(self):
        installed = PACKAGES.installed_packages('libexpat1:amd64\tinstalled\t\nphp-gd\tconfig-files\t\n')
        self.assertEqual(installed, {'libexpat1'})
        with patch.object(PACKAGES.subprocess, 'check_output', return_value='php-gd\tconfig-files\t\n'), \
                self.assertRaisesRegex(ValueError, 'php-gd'):
            PACKAGES.check_packages({'php-gd'})

    def test_virtual_providers_are_accepted_only_when_installed(self):
        installed = PACKAGES.installed_packages(
            'php8.4-cli\tinstalled\tphp-json, php8.4-json ( = 8.4.1 )\n'
            'old-php\tconfig-files\tphp-imap\n')
        self.assertTrue({'php8.4-cli', 'php-json', 'php8.4-json'} <= installed)
        self.assertNotIn('php-imap', installed)

    def test_mysqlcheck_missing_or_broken_blocks_validation(self):
        with patch.object(PACKAGES.shutil, 'which', side_effect=lambda name: None if name == 'mysqlcheck' else '/bin/' + name), \
                self.assertRaisesRegex(ValueError, 'mysqlcheck'):
            PACKAGES.check_tools()
        def execute(argv, **kwargs):
            if argv[0] == 'mysqlcheck':
                raise subprocess.CalledProcessError(1, argv)
            return subprocess.CompletedProcess(argv, 0)
        with patch.object(PACKAGES.shutil, 'which', return_value='/bin/tool'), \
                patch.object(PACKAGES.subprocess, 'run', side_effect=execute), \
                self.assertRaises(subprocess.CalledProcessError):
            PACKAGES.check_tools()

    def test_installed_php_package_with_unloaded_extension_blocks_validation(self):
        with patch.object(PACKAGES.shutil, 'which', return_value='/bin/tool'), \
                patch.object(PACKAGES.subprocess, 'run'), \
                patch.object(PACKAGES.subprocess, 'check_output', return_value='["json"]'), \
                self.assertRaisesRegex(ValueError, 'pdo_mysql'):
            PACKAGES.check_tools()


if __name__ == '__main__':
    unittest.main()
