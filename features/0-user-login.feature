Feature: Login in
    In order to login
    As an anonymous user
    I fill in the login form and validate it
    
    Scenario: I login in with good credentials
        Given I am on "/login"
        When I fill in the following
                | _username  | user1       |
                | _password  | password    |
        And I press "Se connecter"
        And I follow redirect
        Then I see the page with the title "Bienvenue sur Todo List,"
        
    Scenario: I login in with bad credentials
        Given I am on "/login"
        When I fill in the following
                | _username  | user2       |
                | _password  | xxxxxxxx    |
        And I press "Se connecter"
        And I follow redirect
        Then I see the button "Se connecter"
        And I see the alert message ".alert.alert-danger"
