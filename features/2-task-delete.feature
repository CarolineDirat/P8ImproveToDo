Feature: Deletion of a new task
    In order to delete a task
    As an authenticated user
    I delete a task        
    
    Scenario: I delete a task that it's mine
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks"
        When I press "delete-task-15"
        Then I follow redirect
        And I see the alert message ".alert.alert-success"
        And I see the page with the title "Liste des tâches"
    
    Scenario: I delete a task with anonymous author as admin 
        Given I am authenticated user as "admin" with "password"
        And I am on "/tasks"
        When I press "delete-task-5"
        Then I follow redirect
        And I see the alert message ".alert.alert-success"
        And I see the page with the title "Liste des tâches"

    Scenario: I want delete a task that is not mine
        Given I am authenticated user as "admin" with "password"
        And I am on "/tasks"
        When I press "delete-task-45"
        Then the response status code should be 403

    
