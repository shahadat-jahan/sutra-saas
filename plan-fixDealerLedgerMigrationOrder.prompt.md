## Plan: Fix dealer-ledger migration order

TL;DR — The failure is caused by two migrations sharing the same timestamp (both 2026_05_31_000001). Laravel ran the `dealer_ledgers` migration before `dealers`, causing a foreign key error. Fix by ensuring `dealers` migration runs first (rename one migration file to a later timestamp), then run migrations again. This is safe locally but avoid renaming already-run migrations on production; in production create a new migration to add the FK instead.

### Steps
1. Confirm the colliding files:
   - Check `app/Modules/Dealer/Database/Migrations/2026_05_31_000001_create_dealers_table.php`
   - Check `app/Modules/Finance/Database/Migrations/2026_05_31_000001_create_dealer_ledgers_table.php`

2. Rename the ledger migration so it runs after the dealers migration:
   - Rename `app/Modules/Finance/Database/Migrations/2026_05_31_000001_create_dealer_ledgers_table.php` to `2026_05_31_000002_create_dealer_ledgers_table.php`
   - (Alternatively, rename the dealers migration to an earlier timestamp; prefer changing the ledger migration to a later timestamp.)

3. Commit the rename (recommended):
   - Use `git mv` to preserve history, then `git commit -m "Fix migration order: move dealer_ledgers to later timestamp"`.

4. Re-run the migrations locally:
   - Run `php artisan migrate:fresh --seed` and verify migrations complete without the foreign key error.

5. Verify and clean up:
   - Confirm `dealers` table is created before `dealer_ledgers`.
   - Check for other migrations that accidentally share the same timestamp and fix them similarly.

### Further Considerations
1. Production caution: Do NOT rename migrations that have already run in production. Instead, create a new migration that adds the missing foreign key (or use a migration that first creates the `dealers` table if missing).
2. Prevent recurrence: Search for duplicate migration timestamps across `app/Modules/**/Database/Migrations` and ensure timestamps are unique.
3. Migration filenames use timestamps for ordering; changing filenames is safe locally because these migrations use anonymous classes (no class-name changes required), but always test in a staging environment.

---

This is a draft plan. Would you like me to:
- (A) Provide the exact git/mv commands and the recommended timestamp string to use, or
- (B) Scan the repository for other duplicate timestamps now and include a full list?

