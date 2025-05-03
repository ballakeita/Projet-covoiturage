DO $$
DECLARE
    r RECORD;
BEGIN
    -- Désactiver les contraintes de clé étrangère (mode "réplica")
    EXECUTE 'SET session_replication_role = replica';

    -- Supprimer toutes les tables du schéma courant
    FOR r IN (
        SELECT tablename
        FROM pg_tables
        WHERE schemaname = current_schema()
    ) LOOP
        EXECUTE format('DROP TABLE IF EXISTS %I CASCADE', r.tablename);
    END LOOP;

    -- Réactiver les contraintes
    EXECUTE 'SET session_replication_role = DEFAULT';
END $$;