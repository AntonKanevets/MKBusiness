<?php
/*
	task
	1. Напишите функцию подготовки строки, которая заполняет шаблон данными из указанного объекта
	2. Пришлите код целиком, чтобы можно его было проверить
	3. Придерживайтесь code style текущего задания
	4. По необходимости - можете дописать код, методы
	5. Разместите код в гите и пришлите ссылку
*/

/**
 * Класс для работы с API
 *
 * @author		Kanevets Anton
 * @version		v.2.0 (27/03/2024)
 */
class Api
{
    public function __construct(
        private readonly UserToRouteConverter $user_to_route_converter,
    ) {
    }

    /**
     * Заполняет строковый шаблон template данными из объекта object
     *
     * @author		Kanevets Anton
     * @version		v.2.0 (27/03/2024)
     * @param		User $user
     * @param		string[] $templates
     * @return		string[]
     */
    public function get_api_path(User $user, array $templates) : array
    {
        $result = [];

        foreach ($templates as $template) {
            $result[] = $this->user_to_route_converter->convert($user, $template);
        }

        return $result;
    }

    /**
     * Формирует ответ Апи
     *
     * @author		Kanevets Anton
     * @version		v.2.0 (27/03/2024)
     * @param		string[] $result
     * @return		void
     */
    public function response(array $result): void
    {
        echo json_encode($result, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
    }
}

/**
 * Класс UserFactory
 *
 * @author		Kanevets Anton
 * @version		v.2.0 (27/03/2024)
 */
class UserFactory
{
    /**
     * Создает объект User из массива данных
     *
     * @author		Kanevets Anton
     * @version		v.2.0 (27/03/2024)
     * @param		array $user_data
     * @return		User
     * @throws      Exception
     */
    public function build(array $user_data): User
    {
        $user_id = $user_data['id'] ?? null;
        $user_name = $user_data['name'] ?? null;
        $user_role = $user_data['role'] ?? null;
        $user_salary = $user_data['salary'] ?? null;

        $valid_user = $user_id && $user_name && $user_role && $user_salary;

        if (!$valid_user) {
            throw new Exception('User data is not valid');
        }

        return new User(id: $user_id, name: $user_name, role: $user_role, salary: $user_salary);
    }
}

/**
 * Класс User
 *
 * @author		Kanevets Anton
 * @version		v.2.0 (27/03/2024)
 */
class User
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $role,
        private readonly int $salary,
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return int
     */
    public function getSalary(): int
    {
        return $this->salary;
    }

}

/**
 * Класс для конвертации данных объекта User в переданные шаблоны
 *
 * @author		Kanevets Anton
 * @version		v.2.0 (27/03/2024)
 */
class UserToRouteConverter
{
    /**
     * Заполняет строковый шаблон template данными из объекта User
     *
     * @author		Kanevets Anton
     * @version		v.2.0 (27/03/2024)
     * @param		User $user
     * @param		string $template
     * @return		string
     */
    public function convert(User $user, string $template): string
    {
        $result = str_replace('%id%', $user->getId(), $template);
        $result = str_replace('%name%', $user->getName(), $result);
        $result = str_replace('%role%', $user->getRole(), $result);
        $result = str_replace('%salary%', $user->getSalary(), $result);

        return str_replace(' ', '%20', $result);
    }
}

$user_data =
    [
        'id'		=> 20,
        'name'		=> 'John Dow',
        'role'		=> 'QA',
        'salary'	=> 100
    ];

$templates = [
    "/api/items/%id%/%name%",
    "/api/items/%id%/%role%",
    "/api/items/%id%/%salary%"
];

$api = new Api(new UserToRouteConverter());
$user = (new UserFactory())->build($user_data);
$api_Paths = $api->get_api_path($user,$templates);
$api->response($api_Paths);
