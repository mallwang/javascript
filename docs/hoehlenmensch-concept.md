# hoehlenmensch: concept ideas (Angular prototype)

Source: an unfinished Angular 2 / Clarity UI prototype from the author's GitLab account (not part of the repo). It contains no game logic, backend or login, only UI mock-ups and concept notes (German originals, translated here). Treat it as a backlog for features beyond the PHP legacy scope (see `docs/hoehlenmensch.md`). Images: `docs/assets/hoehlenmensch/`.

Planned pages: Home, Work, Crafting, Character, Skills, Settings (login and 404 are stubs).

## Home

- Show the player's home, or the tribe's home if in a tribe; home and tribe home can be extended.
- Buildings: fire camp, well (water), crafting places (tools, clothes, cooking, healing items).
- The more players in a tribe, the more fire camps are needed (one per player and family).

## Work

- Work options: gather wood, stones, water; hunt. Each needs different skill levels.
- Injuries (accidents, wild animal attacks while hunting) need healing time; healing only with healing items.
- Tools increase the output but can break; clothes reduce the injury chance.
- Every work type gives EXP; mini games could give an EXP bonus.
- Durations: 30 / 60 / 120 min.

### General activities (no skills needed, hands suffice)

| Activity | Tool | Skills | Notes |
|----------|------|--------|-------|
| Find water | container | strength, stamina | Stock in the camp; amount depends on strength and stamina. Without a container the player only drinks until not thirsty. Unit: liter. |
| Gather firewood | carrying basket | strength, dexterity | Fire and primitive tools (small animal traps, small wooden container). |
| Gather stones | carrying basket | strength, stamina | Primitive buildings (fire places, storage). |
| Gather flint | carrying basket | dexterity, stamina | Starting fires; primitive items (traps, basket, clothes). |

Images: `wasser.jpg`, `feuerholz.jpg`/`.png`, `steine.jpg`, `feuerstein.jpg`.

### Hunting

Mainly food; chance for bones and hides (depends on carrying capacity and how badly the hide was damaged). The player cannot do anything else while hunting. First activity: hunt small animals (tool: spear; skills: strength, speed).

## Crafting

- Cook, craft tools, make clothes and healing items; needs skills.
- Cooking: water, meat, fish, vegetables, fruit.
- Tools: stones (different types), wood (different types), plant fibre.
- Clothes: water, hides (different types), bones, plant fibre.
- Healing items: water, herbs, flowers, vegetables, fruit.

## Character

- Show injuries and heal; eat, drink, sleep; put on clothes, use tools.
- The player can find a wife (acts independently) and have children.

## Skills

- Different EXP per action; EXP is spent on skill trees and sub-skills.
- Joining a profession gives an EXP bonus for all actions of its family; required EXP rises with level.
- Skill trees: hunting, gathering, fighting, crafting, building. Sub-skills e.g. hunting: small animals, big animals, fishing.

## Settings

First name, last name, username, e-mail, change e-mail, change password.
