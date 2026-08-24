# AdSense readiness audit

Audit date: 2026-08-15

## Implemented

- Public `ads.txt` with the configured publisher ID
- AdSense site ownership meta tag with the same publisher ID
- Crawlable WordPress sitemap
- Explicit `robots.txt` with sitemap declaration
- About and editorial-policy pages
- Persistent footer links to trust, policy, and contact pages
- Article author/editorial disclosure, dates, and correction contact
- Organization, WebSite, and Article structured data
- Per-page descriptions, article breadcrumbs, and category-related reading links
- Advertising, affiliate, and AI-image disclosure page
- `noindex` on search, 404, author, date, and tag utility screens
- Author and tag archives removed from the XML sitemap
- Responsive light/dark mode and accessible navigation

## Editorial work still required

Approval is decided by Google and cannot be guaranteed by code. Before applying, manually review each priority article for:

1. A direct answer and clear scope near the beginning.
2. Original observations, examples, screenshots, photos, calculations, or comparison tables where available.
3. The date and place to which prices or procedures apply.
4. Links to relevant official institutions or service providers.
5. Practical limitations, exceptions, and reader decision criteria.
6. A concise FAQ based on genuine reader questions.
7. A factual review for policy, finance, legal, and safety claims.

Do not invent first-hand experience, sources, credentials, prices, or update dates. Do not bulk-generate near-duplicate articles merely to increase the post count.

## 2026-08-24 content curation

The following overlapping or mainly generic legacy posts are automatically
moved to draft by `adsense-content-curation.php`: 23, 27, 31, 33, 43, and 47.
Their former URLs permanently redirect to a stronger first-hand guide with the
same reader intent. Nothing is deleted, and increasing the curation revision
allows a future editorial pass to change the selection safely.

Public author labels now use `내일의 생활 편집팀` without modifying the
WordPress administrator account. The fortune tool remains `noindex` and is
excluded from the XML sitemap.
