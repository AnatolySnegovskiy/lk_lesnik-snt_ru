<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114095712 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $user = new User();
        $user->setPersonalAccount('admin');
        $passwordHasherFactory = new PasswordHasherFactory([
            User::class => ['algorithm' => 'auto'],
            PasswordAuthenticatedUserInterface::class => [
                'algorithm' => 'auto',
                'cost' => 15,
            ],
        ]);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $user->getUserInfo()
            ->setPhone('')
            ->setAddress('')
            ->setEmail('SuperAdmin1001')
            ->setName('ADMIN');
        $user->setPassword($passwordHasherFactory->getPasswordHasher($user)->hash('Xun7AkRyWJqXzy9iak5LXQL9'));
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
