<?php

declare(strict_types=1);

namespace App\Tests\Controller\Cost;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ViewControllerTest extends WebTestCase
{
    /**
     * Проверка корректного запроса
     */
    public function testGetTravelCostSuccess(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.2020',
            'date_travel_start' => '01.01.2020',
            'date_payment' => '01.01.2020',
        ]);

        $response = $client->getResponse();
        
        // Проверка, что ответ успешен
        $this->assertEquals(200, $response->getStatusCode());

        // Декодирование JSON-ответа
        $data = json_decode($response->getContent(), true);
        
        // Проверка структуры ответа
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('success', $data['status']);
        $this->assertArrayHasKey('cost', $data);
    }

    /**
     * Проврка расчёта нанных
     */
    public function testValidationAgeDiscount(): void
    {
        $client = static::createClient();

        // Скидка с 3-х лет 80%
        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.2020',
            'date_travel_start' => '01.01.2023',
            'date_payment' => null,
        ]);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2000, $data['cost']);

        // Скидка с 6-х лет 30%, не более 4500
        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.2020',
            'date_travel_start' => '01.01.2026',
            'date_payment' => null,
        ]);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        
        // Проверка конечной стоимости
        $this->assertEquals(3000, $data['cost']);
        
        // Скидка с 12-х лет 10% (до 18-ти)
        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.2020',
            'date_travel_start' => '01.01.2032',
            'date_payment' => null,
        ]);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        
        // Проверка конечной стоимости
        $this->assertEquals(9000, $data['cost']);
    }

    /**
     * Проврка расчёта нанных
     */
    public function testValidationEarlyBookingDiscount(): void
    {
        $client = static::createClient();

        // Скидка 7%
        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.1990',
            'date_travel_start' => '01.05.2027',
            'date_payment' => '30.11.2026',
        ]);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        // Проверка конечной стоимости
        $this->assertEquals(9300, $data['cost']);
        
        // Скидка 5%
        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.1990',
            'date_travel_start' => '01.05.2027',
            'date_payment' => '31.12.2026',
        ]);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        // Проверка конечной стоимости
        $this->assertEquals(9500, $data['cost']);
        
        // Скидка 3%
        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.1990',
            'date_travel_start' => '01.05.2027',
            'date_payment' => '31.01.2027',
        ]);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        // Проверка конечной стоимости
        $this->assertEquals(9700, $data['cost']);
    }

    /**
     * Проверка запроса с ошибкой базовой стоимости
     */
    public function testInvalidBaseCost(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/traval/cost', [
            'base_cost' => '',
            'date_of_birth' => '01.01.2020',
            'date_travel_start' => '01.01.2020',
            'date_payment' => '01.01.2020',
        ]);

        $response = $client->getResponse();
        
        // Проверка, что вернулся код ошибки
        $this->assertEquals(400, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        
        // Проверка наличия ошибок
        $this->assertArrayHasKey('errors', $data);
        $this->assertNotEmpty($data['errors']);

        // Проверка наличия ошибки по полю baseCost
        $this->assertContains([
            'field' => 'baseCost',
            'message' => 'Базовая стоимость должна быть заполнена'
        ], $data['errors']);

        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 0,
            'date_of_birth' => '01.01.2020',
            'date_travel_start' => '01.01.2020',
            'date_payment' => '01.01.2020',
        ]);

        $response = $client->getResponse();
        
        // Проверка, что вернулся код ошибки
        $this->assertEquals(400, $response->getStatusCode());

        // Декодирование JSON-ответа
        $data = json_decode($response->getContent(), true);
        
        // Проверка наличия ошибок
        $this->assertArrayHasKey('errors', $data);
        $this->assertNotEmpty($data['errors']);

        // Проверка наличия ошибки по полю baseCost
        $this->assertContains([
            'field' => 'baseCost',
            'message' => 'Базовая стоимость должна быть больше нуля'
        ], $data['errors']);
    }

    /**
     * Проверка запроса с ошибкой даты рождения
     */
    public function testInvalidDateOfBirth(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '',
            'date_travel_start' => '01.01.2020',
            'date_payment' => '01.01.2020',
        ]);

        $response = $client->getResponse();
        
        // Проверка, что вернулся код ошибки
        $this->assertEquals(400, $response->getStatusCode());

        // Декодирование JSON-ответа
        $data = json_decode($response->getContent(), true);
        
        // Проверка наличия ошибок
        $this->assertArrayHasKey('errors', $data);
        $this->assertNotEmpty($data['errors']);

        // Проверка наличия ошибки по полю dateOfBirth
        $this->assertContains([
            'field' => 'dateOfBirth',
            'message' => 'Дата рождения участника должна быть заполнена'
        ], $data['errors']);

        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => 'invalid-date',
            'date_travel_start' => '01.01.2020',
            'date_payment' => '01.01.2020',
        ]);

        $response = $client->getResponse();
        
        // Проверка, что вернулся код ошибки
        $this->assertEquals(400, $response->getStatusCode());

        // Декодирование JSON-ответа
        $data = json_decode($response->getContent(), true);
        
        // Проверка наличия ошибок
        $this->assertArrayHasKey('errors', $data);
        $this->assertNotEmpty($data['errors']);

        // Проверка наличия ошибки по полю dateOfBirth
        $this->assertContains([
            'field' => 'dateOfBirth',
            'message' => 'Дата рождения участника должна быть в формате дд.мм.гггг'
        ], $data['errors']);
    }

    /**
     * Проврка даты старта и даты оплаты
     */
    public function testInvalidDateTravelStartAndDatePayment(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/traval/cost', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.2020',
            'date_travel_start' => 'invalid-date',
            'date_payment' => 'invalid-date',
        ]);

        $response = $client->getResponse();
        
        // Проверка, что вернулся код ошибки
        $this->assertEquals(400, $response->getStatusCode());

        // Декодирование JSON-ответа
        $data = json_decode($response->getContent(), true);
        
        // Проверка наличия ошибок
        $this->assertArrayHasKey('errors', $data);
        $this->assertNotEmpty($data['errors']);

        // Проверка наличия ошибки по полю dateTravelStart
        $this->assertContains([
            'field' => 'dateTravelStart',
            'message' => 'Дата старта путешествия указана не корректно'
        ], $data['errors']);

        $this->assertContains([
            'field' => 'dateTravelStart',
            'message' => 'Дата старта путешествия должна быть в формате дд.мм.гггг'
        ], $data['errors']);

        // Проверка наличия ошибки по полю datePayment
        $this->assertContains([
            'field' => 'datePayment',
            'message' => 'Дата оплаты указана не корректно'
        ], $data['errors']);

        $this->assertContains([
            'field' => 'datePayment',
            'message' => 'Дата оплаты должна быть в формате дд.мм.гггг'
        ], $data['errors']);
    }

    /**
     * Проврка нескществующего маршрута
     */
    public function testInvalidRout(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/traval/invalid-route', [
            'base_cost' => 10000,
            'date_of_birth' => '01.01.2020',
            'date_travel_start' => '01.01.2020',
            'date_payment' => '01.01.2020',
        ]);

        $response = $client->getResponse();
        
        // Проверка, что вернулся код ошибки
        $this->assertEquals(404, $response->getStatusCode());
    }
}
