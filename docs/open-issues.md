# Bugs ouverts

## AII-001 - Header sticky inactif en local

- Statut : rouvert le 16 juillet 2026, cause reelle a identifier.
- Signale le : 15 juillet 2026.
- Environnement constate : `http://atelierivoincidit.test` sous Herd.

Le header ne conservait pas son comportement sticky lors du defilement. Aucun
ancetre bloquant n'a ete trouve. Le positionnement a ete renforce pour WebKit
et les axes logiques avec `inset-block-start` et `align-self`.

La validation utilisateur a montre que ce renforcement ne corrige le header ni
en local dans Safari, ni sur le site distant. L'historique Git confirme que
`position: sticky` et `top: 0` etaient deja presents depuis le 31 mai 2026 : le
probleme est donc anterieur a la migration et ne doit pas etre considere comme
un simple oubli de la propriete CSS.
