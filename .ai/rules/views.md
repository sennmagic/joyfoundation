---
paths:
  - 'resources/views/**'
---

# Views

## Gate Figma-fidelity layout switches on xl:, not lg:
Figma frames for this project are 1440px wide. Any layout decision calibrated against that width — forced `<br>` breaks tuned to a specific column width, multi-column grids with pixel-derived proportions (e.g. ImpactNumbers' grid-cols-6/col-span-2) — must be gated behind the `xl:` breakpoint (1280px), not `lg:` (1024px).

Why: between 1024–1279px, `lg:` already switches to the multi-column/desktop layout, but the viewport isn't wide enough yet for the assumed column width, so forced breaks and grid proportions fragment text badly (confirmed bug in Mission/Vision/Guides text and the ImpactNumbers stat grid — see rich-text.blade.php and home.blade.php).

Simple show/hide toggles that don't depend on exact column width (nav pill visibility, social rail visibility) are fine at `lg:` — this rule only applies to layouts with width-sensitive fidelity baked in.
