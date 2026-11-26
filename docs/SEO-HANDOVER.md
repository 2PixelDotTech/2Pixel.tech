# SEO handover: Two Pixel

## 1. Business summary (taken from the Figma design)

| | |
|---|---|
| Business | Two Pixel |
| Offer | Custom business systems, workflow automation, web & mobile applications, system modernisation |
| Audience | Growing businesses (any country) that run on spreadsheets, WhatsApp, paper and manual admin, including medical practices, property management, logistics, retail/inventory and professional services |
| Market | **International.** The site doesn't target one country. Based in South Africa (stated in the footer and schema `foundingLocation`). Domain: `2pixels.tech` |
| Main conversion | Contact form ("Start the Conversation") / discovery call |

## 2. Keyword research: method and limits

**No search-volume or keyword-difficulty data was available in this session**, so none is quoted here. The list below is a **reasoned strategy** based on what the business sells, what its audience would type, and search intent. Before launch, check volumes in Google Keyword Planner (set location to *All locations*, then compare your top markets such as the US, UK and South Africa) (free with a Google Ads account) or a tool such as Ahrefs or Semrush. After launch, use Google Search Console query data.

### Primary keywords
| Keyword | Intent | Page | Why it fits | Placement | Volume |
|---|---|---|---|---|---|
| custom business software | Commercial | `/` | Describes the core offer, not tied to a location | Title, meta description, Organization schema | Unverified |
| workflow automation (services) | Commercial | `/` | Second core offer; strong buyer intent | Title ("…& Workflow Automation") | Unverified |
| custom software development company | Commercial | `/` | The broader category term buyers use | Meta description, body copy | Unverified |

### Secondary / service keywords (all on `/`, one per service card)
| Keyword | Intent | Section | Volume |
|---|---|---|---|
| business process automation / workflow automation for small business | Commercial | Services → Workflow Automation | Unverified |
| custom business systems / internal business software | Commercial | Services → Custom Business Systems | Unverified |
| web application development / business web applications | Commercial | Services → Web & Mobile Applications | Unverified |
| replace spreadsheets with software / spreadsheet replacement | Problem-aware | Gap section, System Modernisation | Unverified |
| legacy system modernisation | Commercial | Services → System Modernisation | Unverified |
| operations dashboard / operations management software | Commercial | Hero mockup, case study | Unverified |

### Long-tail and industry keywords (good future landing pages; see §6)
- custom software for medical practices
- property management software custom-built
- job tracking software for service businesses
- stock management system for small business
- client portal development
- digitise paper processes small business

### Informational keywords (future blog/guides)
- how to replace spreadsheets with a database or system
- signs your business has outgrown spreadsheets
- off-the-shelf vs custom software for SMEs
- how much does custom software cost *(only write this with real pricing guidance)*

### Transactional
- hire a remote software development team
- outsource custom software development
- book a software consultation

## 3. Keyword-to-page map

| URL | Primary topic | Title (live) | Meta description (live) | H1 |
|---|---|---|---|---|
| `/` | Custom business software & workflow automation | Custom Business Software & Workflow Automation \| Two Pixel | Two Pixel builds custom business systems, workflow automation and web & mobile apps that help growing businesses replace spreadsheets and manual admin. | Replace manual processes with software built for your business. |
| `/operations-management-platform` | Operations management platform (case study) | Operations Management Platform: Concept Case Study \| Two Pixel | How a centralised operations web app can replace spreadsheets, WhatsApp and manual reporting for a service business. A Two Pixel concept project. | Operations Management Platform |
| `/privacy-policy` | Privacy (POPIA) | Privacy Policy \| Two Pixel | How Two Pixel collects, uses and protects your personal information in line with South Africa's Protection of Personal Information Act (POPIA). | Privacy Policy |
| `/404` (any missing URL) | — | Page not found \| Two Pixel | — | noindex |

There is one primary topic per page, so the two pages don't compete for the same terms.

## 4. On-page implementation

- **Headings:** one H1 per page. Each section has an H2 and each card an H3, checked in the browser.
- **Copy:** the design's wording is kept as-is. It already uses the audience's language naturally (spreadsheets, WhatsApp, manual administration, workflow automation, custom applications). No keyword stuffing, and no `meta keywords` tag.
- **Internal links:** nav and footer anchors to every section. The case-study card links to `/operations-management-platform`, which links back to `/#services` and `/#contact`. Breadcrumbs appear on the case-study page.
- **Link text:** descriptive; "Discuss this service" includes a screen-reader-only service name.
- **Images:** the only raster image is the Open Graph image (`two-pixel-custom-business-software-og.png`). The dashboard mockups are HTML with a `role="img"` description.
- **Social:** Open Graph and Twitter card tags on every page (1280×630 image taken from the Figma hero).
- **Canonical:** an absolute canonical URL on every indexable page, built from `SITE_URL`.
- **Language:** `lang="en"` and `og:locale` `en_GB` (matching the British spelling in the copy). There is one language, so no `hreflang` tags are needed.

## 5. Technical SEO

| Item | Status |
|---|---|
| `robots.txt` | Generated by `build.php`; points to the sitemap on the live domain; blocks everything when `ALLOW_INDEXING=false` |
| `sitemap.xml` | Generated by `build.php`, with `lastmod` from each template's modified date. Add new pages to `$pages` in `build.php`. |
| Clean URLs | `/operations-management-platform`; Cloudflare redirects `.html` and `/index.html` to the clean URL |
| HTTPS / canonical host | Cloudflare "Always Use HTTPS" and a www-to-root redirect rule (see `docs/CLOUDFLARE-SETUP.md`); HSTS header in `cloudflare/_headers` |
| 404 | Real HTTP 404 status (Cloudflare `404-page` handling) plus a `noindex` custom page |
| Indexing safety | Live: `index, follow`. Staging: set `ALLOW_INDEXING=false`. |
| Performance | No frameworks, no images to download, deferred JS, ~65 KB HTML. Served from Cloudflare's global network with automatic compression; CSS/JS are cached for a year with `?v=` cache-busting. |
| Mobile | Responsive layouts checked at 375, 768 and 1280 px with no horizontal scroll |

### Structured data (JSON-LD)
- **Home:** `Organization` (name, url, logo, email, areaServed *Worldwide*, foundingLocation South Africa, founders, knowsAbout, an `OfferCatalog` of the four `Service`s) and `WebSite`.
- **Case study:** `BreadcrumbList` and `CreativeWork`.
- **Privacy Policy:** `BreadcrumbList`.
- **Not used on purpose:** `LocalBusiness` (no address or phone yet), reviews or ratings (none exist), prices (none given).
- All blocks parse as valid JSON. Run them through https://search.google.com/test/rich-results after launch.

## 6. International SEO and next steps

You want to compete internationally, so the site deliberately has **no local SEO**: no city in titles, no `LocalBusiness` schema, no Google Business Profile address. Ranking for broad terms like "custom business software" worldwide is very competitive, so growth will come mainly from content and links:

1. **Search Console:** add `2pixels.tech` as a *Domain property*, submit the sitemap, and leave *country targeting* unset (a `.tech` domain is treated as global).
2. **One page per service** (e.g. `/workflow-automation`). Each needs at least 400 words of original content: problems solved, what you build, process and FAQs. Don't duplicate the home page.
3. **Industry pages** only where you can say something specific (real workflows, a real project).
4. **Real case studies**, with permission, naming the client's country. These are strong trust signals for overseas buyers. Keep concept work clearly labelled.
5. **Guides** targeting the informational keywords above. Use universal examples, and give prices in USD alongside other currencies.
6. **Authority:** list Two Pixel on Clutch, GoodFirms and LinkedIn, and use the same name and URL everywhere.
7. **Trust for overseas clients:** mention your time-zone overlap (SAST is UTC+2, which overlaps with UK/EU business hours) and that you offer video calls. The contact form already does.
8. **Optional later:** if a South African audience matters too, add a separate SA services page rather than localising the home page.
