<?php

use PHPUnit\Framework\TestCase;
use Src\Request;
use Controller\Site;
use Model\Postgraduate;

class SiteTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        if (!function_exists('getallheaders')) {
            function getallheaders() {
                return [];
            }
        }
        
        $appConfig = include $_SERVER['DOCUMENT_ROOT'] . '/pop-it-mvc/config/app.php';
        $dbConfig = include $_SERVER['DOCUMENT_ROOT'] . '/pop-it-mvc/config/db.php';
        $pathConfig = include $_SERVER['DOCUMENT_ROOT'] . '/pop-it-mvc/config/path.php';
        
        $GLOBALS['app'] = new Src\Application(new Src\Settings([
            'app' => $appConfig,
            'db' => $dbConfig,
            'path' => $pathConfig,
        ]));
        
        if (!function_exists('app')) {
            function app() {
                return $GLOBALS['app'];
            }
        }
        
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        
        $_SESSION = [];
    }

    /**
     * @dataProvider additionProviderPostgraduate
     * @runInSeparateProcess
     */
    public function testAddPostgraduate(string $name, string $surname, string $patronymic, bool $expected, string $expectedMessage): void
    {
        $_SESSION['id'] = 1;
        
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
    
    public static function additionProviderPostgraduate(): array
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

    /**
     * @dataProvider additionProviderLogin
     * @runInSeparateProcess
     */
    public function testLogin(string $httpMethod, array $userData, string $expectedMessage): void
    {
        $_SESSION = [];
        
        $request = new Request();
        $request->method = $httpMethod;
        $request->set('login', $userData['login']);
        $request->set('password', $userData['password']);
        
        $site = new Site();
        
        ob_start();
        $result = $site->login($request);
        $output = ob_get_clean();
        
        if (strpos($expectedMessage, 'Location:') === 0) {
            $headers = headers_list();
            $found = false;
            foreach ($headers as $header) {
                if (strpos($header, $expectedMessage) !== false) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Редирект на '$expectedMessage' не найден");
        } else {
            $this->assertStringContainsString($expectedMessage, $output);
        }
    }
    
    public static function additionProviderLogin(): array
    {
        return [
            [
                'GET',
                ['login' => '', 'password' => ''],
                'Вход в систему'
            ],
            [
                'POST',
                ['login' => 'wrong_login', 'password' => '123'],
                'Ошибка входа'
            ],
            [
                'POST',
                ['login' => 'admin', 'password' => 'wrong_password'],
                'Ошибка входа'
            ],
        ];
    }
}