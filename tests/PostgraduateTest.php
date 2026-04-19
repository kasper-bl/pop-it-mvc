<?php

use PHPUnit\Framework\TestCase;
use Model\Postgraduate;
use Src\Request;
use Controller\Site;

class PostgraduateTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_URI'] = '/practika/add-postgraduate';
        
        if (!function_exists('getallheaders')) {
            function getallheaders() {
                return [];
            }
        }
        
        $GLOBALS['app'] = new Src\Application(new Src\Settings([
            'app'  => include $_SERVER['DOCUMENT_ROOT'] . '/practika/config/app.php',
            'db'   => include $_SERVER['DOCUMENT_ROOT'] . '/practika/config/db.php',
            'path' => include $_SERVER['DOCUMENT_ROOT'] . '/practika/config/path.php',
        ]));
        
        if (!function_exists('app')) {
            function app() {
                return $GLOBALS['app'];
            }
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['id'] = 1;
    }
    /**
     * @dataProvider additionProvider
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAddPostgraduate(string $name, string $surname, string $patronymic, bool $expected, string $expectedMessage): void
    {

        $request = new Request();
        $request->method = 'POST';
        $request->set('name', $name);
        $request->set('surname', $surname);
        $request->set('patronymic', $patronymic);
        
        $site = new Site();
        
        ob_start();
        $result = $site->addPostgraduate($request);
        $output = ob_get_clean();
        
        if ($expected) {
            $postgraduate = Postgraduate::where('name', $name)
                ->where('surname', $surname)
                ->first();
            
            $this->assertNotNull($postgraduate, 'Аспирант не был добавлен');
            Postgraduate::where('name', $name)->where('surname', $surname)->delete();
        } else {
            $this->assertStringContainsString($expectedMessage, $output);
        }
    }

    public static function additionProvider(): array
    {
        $uniqueName = 'Тест_' . time();
        $uniqueSurname = 'Тестов_' . time();
        
        return [
            [$uniqueName, $uniqueSurname, 'Тестович', true, ''],
            ['', 'Иванов', 'Иванович', false, 'Поле name пусто'],
            ['Иван', '', 'Иванович', false, 'Поле surname пусто'],
            ['', '', 'Иванович', false, 'Поле name пусто'],
        ];
    }
}