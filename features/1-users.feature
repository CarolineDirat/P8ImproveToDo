Feature: User management
    In order to manage users
    As an admin user
    I request users pages

    Background:
        Given I am authenticated user as "admin" with "password"
    
    Scenario: I want the list of users
        And I am on "/"
        When I follow "Admin"
        Then I see the page with the title "Liste des utilisateurs"

    Scenario: I want to create a user
        And I am on "/users"
        When I follow "Créer un utilisateur"
        Then I see the page with the title "Créer un utilisateur"

    Scenario: I want to go back to user list from the page of user creation
        And I am on "/users/create"
        When I follow "Liste des utilisateurs"
        Then I see the page with the title "Liste des utilisateurs"

    Scenario: I want to modify a user
        And I am on "/users"
        When I follow "Modifier"
        Then I see the page with the title "Modifier user1"

     Scenario: I want to go back to user list from the page of user edition
        And I am on "/users/1/edit"
        When I follow "Liste des utilisateurs"
        Then I see the page with the title "Liste des utilisateurs"
        