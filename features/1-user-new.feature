Feature: Creation of a new user
    In order to create a new user
    As an admin user
    I fill in the creation form and validate it

    Background:
        Given I am authenticated user as "admin" with "password"
    
    Scenario: I create a user with good data
        Given I am on "/users/create"
        When I fill in the following
                | user[username]              | user3            |
                | user[plainPassword][first]  | password         |
                | user[plainPassword][second] | password         |
                | user[email]                 | user3@email.com  |
                | user[role]                  | ROLE_ADMIN       |
        And I press "Ajouter"
        And I follow redirect
        Then I see the alert message ".alert.alert-success"
        And I see the page with the title "Liste des utilisateurs"
        And Delete user "user3"
