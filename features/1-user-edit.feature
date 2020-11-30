Feature: Edit a user
    In order to edit a user
    As an admin user
    I fill in the edition form and validate it

    Background:
        Given I am authenticated user as "admin" with "password"
    
    Scenario: I edit a user with good data
        Given I am on "/users/2/edit"
        When I fill in the following
                | user[username]         | user             |
                | user[password][first]  | password         |
                | user[password][second] | password         |
                | user[email]            | user@email.com   |
                | user[role]             | ROLE_ADMIN       |
        And I press "Modifier"
        And I follow redirect
        Then I see the alert message ".alert.alert-success"
        And I see the page with the title "Liste des utilisateurs"
