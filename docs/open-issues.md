# Bugs ouverts

## AII-001 - Header sticky inactif en local

- Statut : ouvert.
- Signale le : 15 juillet 2026.
- Environnement constate : `http://atelierivoincidit.test` sous Herd.

Le header ne conserve pas son comportement sticky lors du defilement. Verifier
le conteneur de defilement, les proprietes `position`, `top`, `overflow` et le
contexte d'empilement avant correction.
