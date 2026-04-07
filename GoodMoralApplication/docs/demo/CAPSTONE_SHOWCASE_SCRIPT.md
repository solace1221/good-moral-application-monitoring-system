# Capstone Showcase Script

## Presentation Outline

Use this script for your capstone panel presentation. Adjust phrasing as needed but keep the core flow.

---

## Opening Statement (1–2 minutes)

> "Good morning/afternoon, Panel. Our capstone project is the Good Moral Application and Monitoring System — GMAMS — developed for Saint Paul University Philippines."
>
> "The problem we addressed: the existing process for issuing Good Moral Certificates is entirely manual. Students fill out paper forms, submit them to the Registrar, and wait for days or weeks. There is no centralized system for tracking applications, validating eligibility, or generating certificates."
>
> "Our system solves this by providing a fully digital, role-based workflow that connects students, deans, program coordinators, the SecOSA office, and administrators in one platform."

---

## System Overview (2–3 minutes)

> "GMAMS is built on Laravel 12, using MySQL and a Blade/Tailwind CSS front-end. It is integrated with the existing Clearance Management System to share the student database — so enrollment data does not need to be entered twice."
>
> "There are seven user roles:"

Walk through the roles briefly:
- **Student** — applies for Good Moral Certificate
- **Admin** — reviews and approves applications, generates certificates
- **Dean** — views college-level applications and violations
- **Program Coordinator** — manages courses and monitors students
- **SecOSA / Moderator** — manages violations
- **PSG Officer** — student government representative with limited access
- **Super Admin** — full system access

---

## Live Demonstration (5–8 minutes)

Follow the [8_MINUTE_DEMO_GUIDE.md](8_MINUTE_DEMO_GUIDE.md) for the timed walkthrough.

---

## Technical Highlights (2 minutes)

> "Some technical highlights of our implementation:"

- **Bidirectional sync** with CMS — account changes in either system propagate to the other.
- **Role-based access control** — all routes are protected by middleware; no cross-role access.
- **Automated certificate generation** — PDF certificates generated server-side with proper formatting.
- **Violation gating** — students with active violations cannot submit applications.
- **Security hardened** — HTTPS enforcement, secure session cookies, parameterized queries, no debug routes in production.

---

## Closing Statement (1 minute)

> "GMAMS successfully digitizes a process that was previously time-consuming and error-prone. It reduces certificate processing time, provides a complete audit trail, and integrates seamlessly with existing SPUP infrastructure."
>
> "We are confident this system is ready for deployment and will provide immediate value to the university community. Thank you."

---

## Anticipated Panel Questions

**Q: Why Laravel?**
> "Laravel provided the fastest path to a secure, well-structured MVC application with built-in authentication, middleware, and ORM support. Its ecosystem aligned well with our team's skills."

**Q: How is data consistency maintained between GMAMS and CMS?**
> "We implement a bidirectional sync service. Profile and account changes in GMAMS are pushed to CMS via `ClearanceSyncService`, and CMS changes are pulled by `GoodMoralSyncService`. Both systems share the same `db-clearance-system` MySQL database to minimize duplication."

**Q: What security measures are in place?**
> "We addressed the OWASP Top 10 relevant to our application: RBAC middleware on all routes, parameterized queries to prevent SQL injection, HTTPS enforcement, secure session cookies, input validation on all forms, and removal of debug routes in production."

**Q: How would you scale this to a larger institution?**
> "The modular structure supports adding colleges or departments with configuration changes. For higher load, the application can be deployed behind a load balancer with Laravel's built-in queue support for async tasks."
