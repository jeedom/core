<?php

use PHPUnit\Framework\TestCase;

class pluginBCTest extends TestCase
{
    const API_FILE = __DIR__.'/api_file';

    public static function getMethodsSignatures(): iterable
    {
        $file = fopen(self::API_FILE, 'rb');
        while ($line = fgets($file)) {
            $signature = trim($line);
            if (empty($signature)) {
                continue;
            }

            $explode = explode(',', $signature);
            yield [
                $explode[0],
                $explode[1],
                $explode[2],
                (bool) $explode[3],
                (bool) $explode[4],
                (bool) $explode[5],
                (bool) $explode[6],
                array_slice($explode, 7),
            ];
        }
    }

    /**
     * @dataProvider getMethodsSignatures
     */
    public function test_required_file_exists(string $class): void
    {
        $file = $this->getFile($class);

        $this->assertFileIsReadable($file, 'File '.$file.' is not readable');
    }

    /**
     * @dataProvider getMethodsSignatures
     */
    public function test_method_exists(string $class, string $method): void
    {
        $this->assertTrue(method_exists($class, $method), 'Method '.$class.'::'.$method.' not found');
    }

    /**
     * @dataProvider getMethodsSignatures
     */
    public function test_return_type(string $class, string $method, string $expectedReturnType, bool $isFinal): void
    {
        $reflectionMethod = new ReflectionMethod($class, $method);

        $reflectionReturnType = $reflectionMethod->getReturnType();
        $actualReturnType = $reflectionReturnType ? $reflectionReturnType->getName() : '';
        $returnTypeAdded = '' === $expectedReturnType && '' !== $actualReturnType;
        if ($isFinal && $returnTypeAdded) {
            $this->noBreakingChangeWarning('Return type added detected on '.$class.'::'.$method.'.');

            return;
        }

        $returnTypeRemoved = '' === $actualReturnType && '' !== $expectedReturnType;
        if ($returnTypeRemoved) {
            $this->noBreakingChangeWarning('Return type removed detected on '.$class.'::'.$method.'.');

            return;
        }

        $this->assertFalse($returnTypeAdded, 'BC: Return type added detected on '.$class.'::'.$method.'.');

        $returnTypeChangedToParent = is_a($expectedReturnType, $actualReturnType, true);
        if ($returnTypeChangedToParent) {
            $this->noBreakingChangeWarning('Return type changed to parent detected on '.$class.'::'.$method.'.');

            return;
        }
        $returnTypeChanged = $expectedReturnType !== $actualReturnType;
        $this->assertFalse($returnTypeChanged, 'BC: Return type changed detected on '.$class.'::'.$method.'.');
    }

    /**
     * @dataProvider getMethodsSignatures
     */
    public function test_final(string $class, string $method, $void, bool $expectedFinal): void
    {
        $reflectionMethod = new ReflectionMethod($class, $method);
        $actualFinal = $reflectionMethod->isFinal();
        $finalRemoved = !$actualFinal && $expectedFinal;
        if ($finalRemoved) {
            $this->noBreakingChangeWarning('Final removed detected on '.$class.'::'.$method.'.');

            return;
        }

        $this->assertSame($expectedFinal, $actualFinal, 'BC: Final addition detected on '.$class.'::'.$method.'.');
    }

    /**
     * @dataProvider getMethodsSignatures
     */
    public function test_abstract(string $class, string $method, $void1, $void2, bool $expectedAbstract): void
    {
        $reflectionMethod = new ReflectionMethod($class, $method);
        $actualAbstract = $reflectionMethod->isAbstract();
        $abstractRemoved = !$actualAbstract && $expectedAbstract;
        if ($abstractRemoved) {
            $this->noBreakingChangeWarning('Abstract removed detected on '.$class.'::'.$method.'.');

            return;
        }

        $this->assertSame($expectedAbstract, $actualAbstract, 'BC: Abstract addition detected on '.$class.'::'.$method.'.');
    }

    /**
     * @dataProvider getMethodsSignatures
     */
    public function test_visibility(string $class, string $method, $void1, $void2, $void3, bool $expectedPublic): void
    {
        $reflectionMethod = new ReflectionMethod($class, $method);
        $actualPublic = $reflectionMethod->isPublic();
        $publicAdded = $actualPublic && !$expectedPublic;
        if ($publicAdded) {
            $this->noBreakingChangeWarning('Public visibility added detected on '.$class.'::'.$method.'.');

            return;
        }

        $this->assertSame($expectedPublic, $actualPublic, 'BC: Public visibility deletion detected on '.$class.'::'.$method.'.');
    }

    /**
     * @dataProvider getMethodsSignatures
     */
    public function test_static(string $class, string $method, $void1, $void2, $void3, $void4, bool $expectedStatic): void
    {
        $reflectionMethod = new ReflectionMethod($class, $method);
        $actualStatic = $reflectionMethod->isStatic();
        $staticAdded = $actualStatic && !$expectedStatic;
        if ($staticAdded) {
            $this->noBreakingChangeWarning('Static added detected on '.$class.'::'.$method.'.');

            return;
        }

        $this->assertSame($expectedStatic, $actualStatic, 'BC: Static deletion detected on '.$class.'::'.$method.'.');
    }

    /**
     * @dataProvider getMethodsSignatures
     */
    public function test_parameters(string $class, string $method, $void1, $void2, $void3, $void4, $void5, array $expectedParameters): void
    {
        $reflectionMethod = new ReflectionMethod($class, $method);
        $arguments = $reflectionMethod->getParameters();
        foreach ($arguments as $count => $argument) {
            $this->assertNotCount(0, $expectedParameters, 'BC: Parameter added on '.$class.'::'.$method.'.');
            $expectedType = (string) array_shift($expectedParameters);
            $actualTypeReflection = $argument->getType();
            $actualType = null === $actualTypeReflection ? '' : $actualTypeReflection->getName();
            $this->assertSame($expectedType, $actualType, 'BC: Parameter type '.$count.' changed on '.$class.'::'.$method.'.');

            $expectedName = (string) array_shift($expectedParameters);
            $this->assertSame($expectedName, $argument->getName(), 'BC: Parameter name '.$count.' changed on '.$class.'::'.$method.'.');

            $expectedDefault = (bool) array_shift($expectedParameters);
            $actualDefault = $argument->isDefaultValueAvailable();
            $this->assertSame($expectedDefault, $actualDefault, 'BC: Parameter default value '.$count.' changed on '.$class.'::'.$method.'.');
        }

        if (count($expectedParameters) > 0) {
            $this->noBreakingChangeWarning('Parameter removed on '.$class.'::'.$method.'.');
        }

        $this->assertTrue(true);
    }

    public static function generateMethodsSignatures(): void
    {
        $flag = 0;
        foreach (glob(dirname(__DIR__, 2).'/core/class/*.php') as $file) {
            $class = basename($file, '.class.php');
            require_once $file;

            $reflection = new ReflectionClass($class);
            foreach ($reflection->getMethods() as $method) {
                if ($method->isPrivate()) {
                    continue;
                }
                $a = [];
                $a[] = $method->getDeclaringClass()->getName();
                $a[] = $method->getName();
                $a[] = $method->getReturnType() ?? '';
                $a[] = (int) $method->isFinal();
                $a[] = (int) $method->isAbstract();
                $a[] = (int) $method->isPublic();
                $a[] = (int) $method->isStatic();
                foreach ($method->getParameters() as $parameter) {
                    $reflectionNamedType = $parameter->getType();
                    $a[] = null === $reflectionNamedType ? '' : $reflectionNamedType->getName();
                    $a[] = $parameter->getName();
                    $a[] = $parameter->isOptional();
                }
                file_put_contents(self::API_FILE, implode(',', $a)."\n", $flag);
                $flag = FILE_APPEND;
            }
        }
    }

    private function getFile(string $class): string
    {
        return dirname(__DIR__, 2).'/core/class/'.$class.'.class.php';
    }

    public function noBreakingChangeWarning(string $str): void
    {
        $this->addWarning($str.'It is not a breaking change, but you need to regenerate the api_file.');
    }
}
