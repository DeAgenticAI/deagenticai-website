# WordPress Implementation: Author Page + Organization Schema + sameAs Profiles

You are implementing two things on deagentic.ai (WordPress):
1. An author page for Reza Jahankohan with Person schema
2. Organization + WebSite schema on the homepage

IMPORTANT: This site implements all JSON-LD schema directly in theme code — NOT via
RankMath or any plugin. Add all schema as <script type="application/ld+json"> blocks
injected into the <head> via the theme's header template or a dedicated schema function
in functions.php. Do not use RankMath schema settings or shortcodes.

---

## TASK 1 — Author Page

### Create a WordPress Page

- URL slug: reza-jahankohan (final URL: /authors/reza-jahankohan/)
- Page title: Reza Jahankohan
- Parent page: authors (create /authors/ as a parent page if it doesn't exist)
- Template: use the most minimal full-width template available — no sidebar, no date
- Status: Published
- Do NOT create as a Post — must be a static Page with no date stamp

### Page body content:

```
Reza Jahankohan is the Founder of DeAgenticAI, building governance infrastructure
for autonomous AI agents operating on-chain.

Connect: LinkedIn · Twitter/X
```

Link "LinkedIn" to https://www.linkedin.com/in/rezajahankohan/
Link "Twitter/X" to https://x.com/RJahankohan

### Person schema — inject into <head> on this page only:

Conditionally output this block when is_page('reza-jahankohan') is true:

```php
function deagenticai_author_schema() {
    if ( is_page( 'reza-jahankohan' ) ) {
        echo '<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Reza Jahankohan",
  "jobTitle": "Founder",
  "url": "https://deagentic.ai/authors/reza-jahankohan/",
  "worksFor": {
    "@type": "Organization",
    "name": "DeAgenticAI",
    "url": "https://deagentic.ai"
  },
  "sameAs": [
    "https://www.linkedin.com/in/rezajahankohan/",
    "https://x.com/RJahankohan"
  ]
}
</script>';
    }
}
add_action( 'wp_head', 'deagenticai_author_schema' );
```

---

## TASK 2 — Organization + WebSite Schema (Homepage only)

Inject both blocks into <head> on the homepage only (is_front_page()):

```php
function deagenticai_homepage_schema() {
    if ( is_front_page() ) {
        echo '<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "DeAgenticAI",
  "url": "https://deagentic.ai",
  "description": "DeAgenticAI\'s Agentic Control Plane enforces cryptographic policy over AI agent authority — separating what an agent can do from what it is authorized to do — in Web3 and enterprise financial environments.",
  "logo": "https://deagentic.ai/wp-content/themes/DeAgenticAI/images/logo.png",
  "foundingDate": "2026",
  "sameAs": [
    "https://github.com/DeAgenticAI",
    "https://www.linkedin.com/company/deagenticai",
    "https://x.com/RJahankohan"
  ],
  "product": {
    "@type": "SoftwareApplication",
    "name": "Agentic Control Plane",
    "applicationCategory": "AI Agent Infrastructure"
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "DeAgenticAI",
  "url": "https://deagentic.ai",
  "description": "Governance infrastructure for autonomous AI agents — cryptographic policy enforcement for Web3 and enterprise financial environments.",
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "https://deagentic.ai/?s={search_term_string}"
    },
    "query-input": "required name=search_term_string"
  }
}
</script>';
    }
}
add_action( 'wp_head', 'deagenticai_homepage_schema' );
```

---

## TASK 3 — Homepage Title Tag

Verify the homepage <title> outputs exactly:

```
DeAgenticAI — Agentic Control Plane for AI Agent Authority in Web3 and Enterprise
```

This is exactly 60 characters. If the theme controls this via wp_title or via a
theme settings panel, update it there. If it's hardcoded in header.php, update
the conditional for is_front_page().

---

## TASK 4 — BreadcrumbList Schema (all non-homepage pages)

Add BreadcrumbList schema to all non-homepage pages. Output conditionally:

```php
function deagenticai_breadcrumb_schema() {
    if ( is_front_page() ) return;

    $items = [];
    $items[] = [
        "@type" => "ListItem",
        "position" => 1,
        "name" => "Home",
        "item" => "https://deagentic.ai/"
    ];

    if ( is_singular() ) {
        global $post;
        $items[] = [
            "@type" => "ListItem",
            "position" => 2,
            "name" => get_the_title( $post->ID ),
            "item" => get_permalink( $post->ID )
        ];
    }

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => $items
    ];

    echo '<script type="application/ld+json">'
        . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
        . '</script>';
}
add_action( 'wp_head', 'deagenticai_breadcrumb_schema' );
```

Extend the $items array for deeper hierarchies (glossary, pillars, guides) as
those page types are built. For now, 2-level breadcrumb (Home → Page) is sufficient.

---

## Verification checklist

After implementing, confirm each with Google Rich Results Test
(https://search.google.com/test/rich-results):

- [ ] /authors/reza-jahankohan/ returns HTTP 200
- [ ] Person schema valid on /authors/reza-jahankohan/
- [ ] Organization schema valid on homepage
- [ ] WebSite schema with SearchAction valid on homepage
- [ ] BreadcrumbList schema present on a non-homepage page
- [ ] Homepage <title> matches required string exactly
- [ ] No duplicate schema blocks in page source
- [ ] Page source shows schema in <head>, not in body
