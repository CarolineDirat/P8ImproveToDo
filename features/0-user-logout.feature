Feature: Logout
    In order to logout
    As an authenticated user
    I click link to logout

    Background:
        Given I am authenticated user as "admin" with "password"
    
    Scenario Outline: I logout from a page
        And I am on "<page>"
        When I follow "<link>"
        Then I follow redirect
        And I see the button "<text>"

        Examples:
            | page                  | link              | text          |
            | /                     | Se déconnecter    | Se connecter  |
            | /tasks/filter/true    | Se déconnecter    | Se connecter  |
            | /tasks/filter/false   | Se déconnecter    | Se connecter  |
            | /tasks/create         | Se déconnecter    | Se connecter  |
            | /tasks/25/edit        | Se déconnecter    | Se connecter  |
            | /users                | Se déconnecter    | Se connecter  |
            | /users/create         | Se déconnecter    | Se connecter  |
            | /users/2/edit         | Se déconnecter    | Se connecter  |
