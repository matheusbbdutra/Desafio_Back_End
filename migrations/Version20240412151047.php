<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412151047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carteira (id SERIAL NOT NULL, usuario_id INT DEFAULT NULL, s_saldo DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_307D6881DB38439E ON carteira (usuario_id)');
        $this->addSql('CREATE TABLE s_usuario (id SERIAL NOT NULL, nome VARCHAR(255) NOT NULL, cpf_cnpj VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, senha VARCHAR(255) NOT NULL, is_logista BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C2865F75A425330 ON s_usuario (cpf_cnpj)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C2865F7E7927C74 ON s_usuario (email)');
        $this->addSql('CREATE TABLE transacoes (id SERIAL NOT NULL, remetente_id INT DEFAULT NULL, destinatario_id INT DEFAULT NULL, valor DOUBLE PRECISION NOT NULL, dt_transacao TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97CF7B5CFA0A674B ON transacoes (remetente_id)');
        $this->addSql('CREATE INDEX IDX_97CF7B5CB564FBC1 ON transacoes (destinatario_id)');
        $this->addSql('ALTER TABLE carteira ADD CONSTRAINT FK_307D6881DB38439E FOREIGN KEY (usuario_id) REFERENCES s_usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transacoes ADD CONSTRAINT FK_97CF7B5CFA0A674B FOREIGN KEY (remetente_id) REFERENCES s_usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transacoes ADD CONSTRAINT FK_97CF7B5CB564FBC1 FOREIGN KEY (destinatario_id) REFERENCES s_usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carteira DROP CONSTRAINT FK_307D6881DB38439E');
        $this->addSql('ALTER TABLE transacoes DROP CONSTRAINT FK_97CF7B5CFA0A674B');
        $this->addSql('ALTER TABLE transacoes DROP CONSTRAINT FK_97CF7B5CB564FBC1');
        $this->addSql('DROP TABLE carteira');
        $this->addSql('DROP TABLE s_usuario');
        $this->addSql('DROP TABLE transacoes');
    }
}
