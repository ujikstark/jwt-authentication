<?php

function getDatabaseConfig(): array {
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=ujik.test;dbname=auth_test_db",
                "username" => "root",
                "password" => "password"
            ],
            "prod" => [
                "url" => "mysql:host=ujik.test;dbname=auth_db",
                "username" => "root",
                "password" => "password"
            ],
        ]
    ];
}