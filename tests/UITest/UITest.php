<?php



use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Extensions\Selenium2TestCase;
use PHPUnit\Extensions\Selenium2TestCase\Keys;
use PHPUnit\Extensions\Selenium2TestCase\SessionCommand\Click;
use PHPUnit\Extensions\Selenium2TestCase\WebDriverException;
use PHPUnit\Extensions\Selenium2TestCase\Window;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\WebDriverBy;

//class UITest extends PHPUnit_Extensions_Selenium2TestCase
class UITest extends \PHPUnit\Framework\TestCase
{
//    private $screenshotPath = 'C:\xampp\htdocs\screenshots\\';
    private $screenshotPath = '.\screenshots\\';
    private $driver;
    private $validAcc = "asdf";
    private $validPas = "asdf";
    private $invalidAcc = "!@#$%^";
    private $invalidPas = "!@#$%^";

    public function setUp() : void
    {
//        $screenshotPath = 'C:\xampp\htdocs\screenshots';
//        $this->listener = new PHPUnit_Extensions_Selenium2TestCase_ScreenshotListener($screenshotPath);
//        $this->setHost('localhost');
//        $this->setPort(4444);
//        $this->setBrowser('chrome');
//        $this->setBrowserUrl('localhost:8000');

        $options = new ChromeOptions();
        $options->addArguments(array(
//            '--headless',
//            '--window-size=1920,1080',
            '--start-maximized',
            '--disable-gpu',
            '--no-sandbox',
        ));
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        $host = 'http://localhost:4444/wd/hub'; // this is the default
//        $host = 'http://192.168.3.131:4444/wd/hub';
        $this->driver = RemoteWebDriver::create($host, $capabilities);
        $this->driver->manage()->timeouts()->implicitlyWait(10);


//        $host = "http://localhost:4444/wd/hub";
//        $profile = new FirefoxProfile();
//        $capabilities = DesiredCapabilities::firefox();
//        $capabilities->setCapability(FirefoxDriver::PROFILE, $profile);
//        $capabilities->setCapability('marionette', true);
//        $this->driver = RemoteWebDriver::create($host, $capabilities);

    }

    public function tearDown(): void
    {
        if($this->hasFailed())
        {
            $this->driver->takeScreenshot($this->screenshotPath . date("Y-m-d hisa") . ".png");
        }
        $this->driver->quit();
    }

    public function login()
    {
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"navbar-static-login\"]/span"))->click();
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"nav_account\"]"))->sendKeys($this->validAcc);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"nav_password\"]"))->sendKeys($this->validPas);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"login\"]/div/div[3]/button"))->click();
        sleep(2);
    }

    public function testOpen()
    {
        $this->driver->get("localhost:8000");
        $this->assertEquals("Any Buy 任購網", $this->driver->getTitle());

//        $this->url("/");
//        sleep(2);
//        $this->assertEquals("Any Buy 任購網", $this->title());
    }

    public function  testLogin()
    {
        $this->driver->get("localhost:8000");
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"navbar-static-login\"]/span"))->click();
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"nav_account\"]"))->sendKeys($this->validAcc);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"nav_password\"]"))->sendKeys($this->validPas);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"login\"]/div/div[3]/button"))->click();
        sleep(2);
        try {
            $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"navbar-account\"]"))->click();
            $name = $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"navbarSupportedContent\"]/div[2]/div/div[2]/div/h6[1]/span"));
        } catch (\Facebook\WebDriver\Exception\WebDriverException $e) {
            $name = $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"login\"]/div/p"));
        }
        $this->assertEquals("Hi, Sd-f,A", $name->getText());
    }

    public function  testLoginFailure()
    {
        $this->driver->get("localhost:8000");
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"navbar-static-login\"]/span"))->click();
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"nav_account\"]"))->sendKeys($this->invalidAcc);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"nav_password\"]"))->sendKeys($this->invalidPas);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"login\"]/div/div[3]/button"))->click();
        sleep(2);
        try {
            $status = ($this->driver->findElement(WebDriverBy::xpath("//*[@id=\"login\"]/div/p")))->getText();
        } catch (\Facebook\WebDriver\Exception\WebDriverException $e) {
            $status = "Login Successfully";
        }
        $this->assertEquals("登入失敗", $status);
    }

    public function testShoppingCart()
    {
        $this->driver->get("localhost:8000");
        $this->login();
        $this->driver->get("http://localhost:8000/shopping-cart");
        $items = $this->driver->findElements(WebDriverBy::xpath("/html/body/main/div/div/div/div/div[2]/form/div[1]/div/table/tbody/tr/th"));
        self::assertEquals(0, sizeof($items));
        $this->driver->get("localhost:8000/products/item/3");
        $this->driver->findElement(WebDriverBy::xpath("/html/body/main/div/div/div[1]/div[2]/div/div[3]/button"))->click();
        sleep(2);
        $this->driver->get("localhost:8000/products/item/4");
        $this->driver->findElement(WebDriverBy::xpath("/html/body/main/div/div/div[1]/div[2]/div/div[3]/button"))->click();
        sleep(2);
        $this->driver->get("http://localhost:8000/shopping-cart");
        $items = $this->driver->findElements(WebDriverBy::xpath("/html/body/main/div/div/div/div/div[2]/form/div[1]/div/table/tbody/tr/th"));
        self::assertEquals(2, sizeof($items));
    }

    public function testBuy()
    {
        $this->driver->get("localhost:8000");
        $this->login();
        // check number of order after buying
        $this->driver->get("localhost:8000/order");
        $orders = $this->driver->findElements(WebDriverBy::xpath("/html/body/main/div/div/div[2]/div/div/table/tbody/tr"));
        // put item 2 into shopping cart
        $this->driver->get("localhost:8000/products/item/3");
        $this->driver->findElement(WebDriverBy::xpath("/html/body/main/div/div/div[1]/div[2]/div/div[3]/button"))->click();
        sleep(2);
        $this->driver->get("http://localhost:8000/shopping-cart");
        sleep(2);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"checkout_info\"]/div[2]/button"))->click();
        sleep(2);
        $buyButton = $this->driver->findElement(WebDriverBy::xpath("/html/body/main/div/div/form/div[1]/div[2]/button"));
        $this->driver->executeScript("window.scrollTo(0,500)");
        $this->driver->findElement(WebDriverBy::xpath("/html/body/main/div/div/form/div[1]/div[2]/button"))->click();
        sleep(2);
        // check whether number of order is equal to n+1
        $this->driver->get("localhost:8000/order");
        $actual = $this->driver->findElements(WebDriverBy::xpath("/html/body/main/div/div/div[2]/div/div/table/tbody/tr"));
        self::assertEquals(sizeof($orders)+1, sizeof($actual));
    }

    public function testAddressManagement()
    {
        $this->driver->get("localhost:8000");
        $this->login();
        $this->driver->get("http://localhost:8000/location");
        $expected = sizeof($this->driver->findElements(WebDriverBy::xpath("/html/body/main/div/div/div[2]/div/div/table/tbody/tr")))+1;
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"newlocation\"]"))->click();
        $location = $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"twzipcode\"]/div[1]/input"));
        $location->sendKeys(\Facebook\WebDriver\WebDriverKeys::DOWN);
        $location->sendKeys(\Facebook\WebDriver\WebDriverKeys::ENTER);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"lo\"]/form/input[1]"))->sendKeys("address of test");
        $this->driver->findElement(WebDriverBy::xpath("//*[@id=\"lo\"]/form/button[1]"))->click();
        $actual = sizeof($this->driver->findElements(WebDriverBy::xpath("/html/body/main/div/div/div[2]/div/div/table/tbody/tr")));
        self::assertEquals($expected, $actual);
    }

//    public function onNotSuccessfulTest($e) {
//        $this->listener->addError($this, $e, microtime(true));
//        parent::onNotSuccessfulTest($e);
//    }

}