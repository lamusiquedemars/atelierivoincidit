# Bugs ouverts

## AII-001 - Header sticky inactif en local

- Statut : corrige localement le 16 juillet 2026, validation visuelle requise.
- Signale le : 15 juillet 2026.
- Environnement constate : `http://atelierivoincidit.test` sous Herd.

Le header ne conservait pas son comportement sticky lors du defilement. Aucun
ancetre bloquant n'a ete trouve. Le positionnement a ete renforce pour WebKit
et les axes logiques avec `inset-block-start` et `align-self`.
