Feature: Modify the state of a task
    In order to modify the state of a task
    As an authenticated user
    I click on the button to modify the state of a task

    Background:
        Given I am authenticated user as "user1" with "password"
    
    Scenario: I want to modify the state of a done task from the list of all tasks
        And I am on "/tasks" 
        When I press "Marquer non terminée"
        Then I see the alert message ".alert.alert-success"

    Scenario: I want to modify the state of a waiting task from the list of all tasks
        And I am on "/tasks" 
        When I press "Marquer comme faite"
        Then I see the alert message ".alert.alert-success"

    @javascript
    Scenario: I want to modify the state of a task from the list of done tasks
        And I am on "/tasks/filter/true" 
        When I follow "Marquer non terminée"
        Then I confirm the popup

    @javascript
    Scenario: I want to modify the state of a task from the list of waiting tasks
        And I am on "/tasks/filter/false" 
        When I follow "Marquer comme faite"
        Then I confirm the popup
