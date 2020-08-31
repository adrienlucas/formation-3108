# Formulaire de contact

## Description
En tant que visiteur, je dois pouvoir contacter
l'admin.

## Régles métiers

 - le visiteur doit fournir une adresse mail et un message
 - l'adresse doit être valide
 - le message ne doit pas dépasser 120 char
 - le message de succès s'affiche après redirection vers la homepage
 - lorsque le visiteur contact l'administrateur, une ligne de log est écrite

## Scenarios d'acceptance
### Happy path
 Sachant que je me rend sur "/contact"
 Lorsque je remplis le champs "email" avec "mon@ema.il"
 Et que je remplis le champs "message" avec "Hello"
 Et que je clique sur "Envoyer"
 Alors je vois un message de succès "Merci d'avoir contacté l'admin"
 
### Sad path
 Sachant que je me rend sur "/contact"
 Lorsque je remplis le champs "email" avec "toto"
 Et que je remplis le champs "message" avec rien
 Et que je clique sur "Envoyer"
 Alors je vois un message d'erreur "Merci de saisir un email valide"
 Et je vois un message d'erreur "Merci de saisir un message de moins de 120 char"/
