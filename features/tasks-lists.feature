Feature: List of tasks
    In order to see a list of tasks
    As an authenticated user
    I ask a page of the list of tasks

    Scenario: I want the list of done tasks from the home page
        Given I am authenticated user as "user1" with "password"
        And I am on the home page 
        When I follow "Consulter la liste des tâches terminées"
        Then I see the page with the title "Liste des tâches terminées"

    Scenario: I want the list of waiting tasks from the home page
        Given I am authenticated user as "user1" with "password"
        And I am on the home page 
        When I follow "Consulter la liste des tâches à faire"
        Then I see the page with the title "Liste des tâches non terminées"

    Scenario: I want the list of all tasks from the home page
        Given I am authenticated user as "user1" with "password"
        And I am on the home page 
        When I follow "Consulter la liste de toutes les tâches"
        Then I see the page with the title "Liste des tâches"
    
    Scenario: I want the list of done tasks from the page of all tasks
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks" 
        When I follow "Consulter la liste des tâches terminées"
        Then I see the page with the title "Liste des tâches terminées"
    
    Scenario: I want the list of waiting tasks from the page of all tasks
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks" 
        When I follow "Consulter la liste des tâches à faire"
        Then I see the page with the title "Liste des tâches non terminées"
    
    Scenario: I want the list of all tasks from the page of done tasks
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/filter/true" 
        When I follow "Consulter la liste de toutes les tâches"
        Then I see the page with the title "Liste des tâches"
    
    Scenario: I want the list of waiting tasks from the page of done tasks
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/filter/true" 
        When I follow "Consulter la liste des tâches à faire"
        Then I see the page with the title "Liste des tâches non terminées"
    
    Scenario: I want the list of all tasks from the page of waiting tasks
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/filter/false" 
        When I follow "Consulter la liste de toutes les tâches"
        Then I see the page with the title "Liste des tâches"
    
    Scenario: I want the list of done tasks from the page of waiting tasks
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/filter/false" 
        When I follow "Consulter la liste des tâches terminées"
        Then I see the page with the title "Liste des tâches terminées"

    Scenario: I want the list of all tasks from the page of task creation
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/create" 
        When I follow "Consulter la liste de toutes les tâches"
        Then I see the page with the title "Liste des tâches"
    
    Scenario: I want the list of done tasks from the page of task creation
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/create" 
        When I follow "Consulter la liste des tâches terminées"
        Then I see the page with the title "Liste des tâches terminées"
    
    Scenario: I want the list of waiting tasks from the page of task creation
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/create" 
        When I follow "Consulter la liste des tâches à faire"
        Then I see the page with the title "Liste des tâches non terminées"

    Scenario: I want the list of all tasks from the page of task edition
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/51/edit" 
        When I follow "Consulter la liste de toutes les tâches"
        Then I see the page with the title "Liste des tâches"
    
     Scenario: I want the list of done tasks from the page of task edition
        Given I am authenticated user as "user1" with "password"
        And I am on "/tasks/51/edit" 
        When I follow "Consulter la liste des tâches terminées"
        Then I see the page with the title "Liste des tâches terminées"
    
    Scenario: I want the list of waiting tasks from the page of task edition
            Given I am authenticated user as "user1" with "password"
            And I am on "/tasks/51/edit" 
            When I follow "Consulter la liste des tâches à faire"
            Then I see the page with the title "Liste des tâches non terminées"
