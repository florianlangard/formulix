# Routes

| route | url | methode | fonction |
| ------- | ------- | ------- | ------- |
| 'login' | '/login' || connexion |
| 'logout' | '/logout' || déconnexion |
| 'register' | '/register' | GET - POST | créer un compte |
| 'home' | '/' || page d'accueil |
| 'predictions_list' | '/predictions' | GET | liste de mes predi |
| 'predictions_add' | '/predictions/{event_id}' | POST | ajouter une predi sur un event |
| 'predictions_edit' | '/prediction/{id}' | PATCH - PUT | modifier une predi |
| 'events_list' | '/events' | GET | afficher liste des events |
| 'event_show' | '/events/{id}' | GET | afficher un event |
