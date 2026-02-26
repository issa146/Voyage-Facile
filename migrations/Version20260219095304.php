<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260219095304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, date DATE NOT NULL, heure TIME DEFAULT NULL, lieu LONGTEXT DEFAULT NULL, voyage_id INT NOT NULL, INDEX IDX_B875551568C9E5AF (voyage_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, formule_id INT NOT NULL, INDEX IDX_6EEAA67D2A68F4D1 (formule_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE formule (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, niveau VARCHAR(50) NOT NULL, user_id INT NOT NULL, INDEX IDX_605C9C98A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, type_paiement VARCHAR(50) NOT NULL, statut VARCHAR(50) NOT NULL, date DATETIME NOT NULL, commande_id INT NOT NULL, INDEX IDX_B1DC7A1E82EA2E54 (commande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(200) NOT NULL, mot_de_passe VARCHAR(150) NOT NULL, date_inscription DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE voyage (id INT AUTO_INCREMENT NOT NULL, destination VARCHAR(100) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, compagnie VARCHAR(50) DEFAULT NULL, aeroport_depart VARCHAR(50) DEFAULT NULL, aeroport_arrivee VARCHAR(50) DEFAULT NULL, prix_vol_estime DOUBLE PRECISION DEFAULT NULL, nom_hebergement VARCHAR(50) DEFAULT NULL, type_hebergement VARCHAR(50) DEFAULT NULL, localisation_hebergement LONGTEXT DEFAULT NULL, prix_hebergement_estime DOUBLE PRECISION DEFAULT NULL, statut VARCHAR(50) NOT NULL, formule_id INT NOT NULL, INDEX IDX_3F9D89552A68F4D1 (formule_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B875551568C9E5AF FOREIGN KEY (voyage_id) REFERENCES voyage (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D2A68F4D1 FOREIGN KEY (formule_id) REFERENCES formule (id)');
        $this->addSql('ALTER TABLE formule ADD CONSTRAINT FK_605C9C98A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D89552A68F4D1 FOREIGN KEY (formule_id) REFERENCES formule (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B875551568C9E5AF');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D2A68F4D1');
        $this->addSql('ALTER TABLE formule DROP FOREIGN KEY FK_605C9C98A76ED395');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E82EA2E54');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D89552A68F4D1');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE formule');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE voyage');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
