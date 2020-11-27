Feature: Creation of a new user
    In order to create a new user
    As an anonymous user
    I fill in the creation form and validate it

    
    Scenario: A create a user with good data
        Given I am on "/users/create"
        When I fill in the following
                | user[username]         | user             |
                | user[password][first]  | password         |
                | user[password][second] | password         |
                | user[email]            | user@email.com   |
                | user[role]             | ROLE_ADMIN       |
        And I press "Ajouter"
        Then I see the alert message ".alert.alert-success" 
