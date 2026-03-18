# Rank Math Utility-Only Setup (Theme Owns Schema)

Updated: March 2026

## Decision

Use theme code as the single source of truth for all JSON-LD schema.
Use Rank Math only for utility features (sitemap, redirects, monitoring, optional analytics).

## Current implementation status in this theme

- Canonical JSON-LD is emitted by theme code in inc/schema.php.
- Additional schema blocks used by content modules are emitted in:
  - inc/marketing-pages.php
  - inc/learn-content.php
- Legacy schema hooks in functions.php are disabled.
- Rank Math schema output is disabled in functions.php via rank_math/schema/markup filter.
- Theme schema now uses a dedicated schema gate function:
  - aura_should_output_theme_schema_tags()

## What to use from Rank Math

Keep enabled:
- Sitemap
- Redirections
- 404 Monitor
- Optional: Analytics module (only if you actively use it)

Disable:
- Schema (Structured Data)
- Breadcrumbs module (if you do not need Rank Math breadcrumb HTML)

Notes:
- JSON-LD BreadcrumbList is emitted by theme schema code.
- If you need visible breadcrumb UI later, render it in theme templates or re-enable Rank Math breadcrumbs for HTML only after checking output.

## Dashboard steps (owner: site admin)

1) Rank Math Dashboard modules
- Go to Rank Math SEO -> Dashboard.
- Turn OFF:
  - Schema (Structured Data)
  - Breadcrumbs
- Turn ON:
  - Sitemap
  - Redirections
  - 404 Monitor

2) Sitemap settings
- Go to Rank Math SEO -> Sitemap Settings.
- Include post types:
  - aura_pillar
  - aura_guide
  - aura_research
  - aura_update
  - aura_compare
  - aura_dataset
- Exclude:
  - attachment
- Save changes.

3) Final dashboard maintenance
- Go to Settings -> Permalinks -> Save Changes.
- Purge all caches (plugin cache, server cache, CDN).

## Guardrails

- Do not configure per-post schema in Rank Math.
- Do not use Rank Math schema templates for these content types.
- Do not re-enable Rank Math Schema unless theme schema output is intentionally changed.

## Validation checklist (owner: assistant)

After dashboard steps are complete, validate by checking rendered HTML/page source:

- Homepage includes Organization + WebSite JSON-LD once.
- Glossary entry includes DefinedTerm JSON-LD once.
- aura_guide single includes TechArticle JSON-LD once.
- aura_research single includes ScholarlyArticle JSON-LD once.
- aura_dataset single includes Dataset JSON-LD once.
- Ecosystem page includes FAQPage JSON-LD.
- BreadcrumbList appears once where applicable.
- No Rank Math schema duplication appears in source.

Validation tools:
- Google Rich Results Test (supported entities)
- Schema.org Validator (full schema types)

## Quick conclusion

For this project, Rank Math should be treated as an SEO utility toolkit, not the schema source.
The theme remains the authoritative schema layer.
