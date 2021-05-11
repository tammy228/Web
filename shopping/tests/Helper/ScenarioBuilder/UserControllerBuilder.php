<?php


namespace App\Tests\Helper\ScenarioBuilder;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserControllerBuilder extends AbstractScenarioBuilder
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * 建立測試 UserController::fetchUser() 的環境
     *
     * UUID List
     * User1: 3396e358-8aee-4a40-8505-5435aca23620
     * User2: 7ef93d26-a5dc-4ac7-af22-b1014f9da5ec
     * User3: d9987dfb-8ac5-48a6-af7c-73c6cf62112a
     * User4: 3d79ac29-a398-45b6-be6d-3fcfb6d1b38b
     * User5: fc1d99c4-a6bf-4748-a27f-c4d5943070a2
     * User6: 11b1ff10-cb7e-443a-a729-5a86db3fc963
     * User7: daf74e43-fe28-4755-a00f-58b9266f8c01
     * User8: a697f8d5-5305-4cac-8bea-31c22a7538f1
     * User9: 2da848dd-38bf-4840-98f7-10ed3ce2bd0a
     * User10: 806a1ebf-d85b-4ac3-8499-8fede9dee6ba
     *
     * User1, User4, User 7 已經被 Deactivated.
     *
     * @throws \Exception
     */
    public function build()
    {
        $testUser1 = new User();
        $testUser1->setUuid("3396e358-8aee-4a40-8505-5435aca23620");
        $testUser1->setUsername("user1");
        $testUser1->setPassword("12345");
        $testUser1->setEmail("user1@gmail.com");
        $testUser1->setDisplayName("Test User 1");
        $testUser1->deactivate();

        $this->entityManager->persist($testUser1);

        $testUser2 = new User();
        $testUser2->setUuid("7ef93d26-a5dc-4ac7-af22-b1014f9da5ec");
        $testUser2->setUsername("user2");
        $testUser2->setPassword("12345");
        $testUser2->setEmail("user2@gmail.com");
        $testUser2->setDisplayName("Test User 2");

        $this->entityManager->persist($testUser2);

        $testUser3 = new User();
        $testUser3->setUuid("d9987dfb-8ac5-48a6-af7c-73c6cf62112a");
        $testUser3->setUsername("user3");
        $testUser3->setPassword("12345");
        $testUser3->setEmail("user3@gmail.com");
        $testUser3->setDisplayName("Test User 3");

        $this->entityManager->persist($testUser3);

        $testUser4 = new User();
        $testUser4->setUuid("3d79ac29-a398-45b6-be6d-3fcfb6d1b38b");
        $testUser4->setUsername("user4");
        $testUser4->setPassword("12345");
        $testUser4->setEmail("user4@gmail.com");
        $testUser4->setDisplayName("Test User 4");
        $testUser4->deactivate();

        $this->entityManager->persist($testUser4);

        $testUser5 = new User();
        $testUser5->setUuid("fc1d99c4-a6bf-4748-a27f-c4d5943070a2");
        $testUser5->setUsername("user5");
        $testUser5->setPassword("12345");
        $testUser5->setEmail("user5@gmail.com");
        $testUser5->setDisplayName("Test User 5");

        $this->entityManager->persist($testUser5);

        $testUser6 = new User();
        $testUser6->setUuid("11b1ff10-cb7e-443a-a729-5a86db3fc963");
        $testUser6->setUsername("user6");
        $testUser6->setPassword("12345");
        $testUser6->setEmail("user6@gmail.com");
        $testUser6->setDisplayName("Test User 6");

        $this->entityManager->persist($testUser6);

        $testUser7 = new User();
        $testUser7->setUuid("daf74e43-fe28-4755-a00f-58b9266f8c01");
        $testUser7->setUsername("user7");
        $testUser7->setPassword("12345");
        $testUser7->setEmail("user7@gmail.com");
        $testUser7->setDisplayName("Test User 7");
        $testUser7->deactivate();

        $this->entityManager->persist($testUser7);

        $testUser8 = new User();
        $testUser8->setUuid("a697f8d5-5305-4cac-8bea-31c22a7538f1");
        $testUser8->setUsername("user8");
        $testUser8->setPassword("12345");
        $testUser8->setEmail("user8@gmail.com");
        $testUser8->setDisplayName("Test User 8");

        $this->entityManager->persist($testUser8);

        $testUser9 = new User();
        $testUser9->setUuid("2da848dd-38bf-4840-98f7-10ed3ce2bd0a");
        $testUser9->setUsername("user9");
        $testUser9->setPassword("12345");
        $testUser9->setEmail("user9@gmail.com");
        $testUser9->setDisplayName("Test User 9");

        $this->entityManager->persist($testUser9);

        $testUser10 = new User();
        $testUser10->setUuid("806a1ebf-d85b-4ac3-8499-8fede9dee6ba");
        $testUser10->setUsername("user10");
        $testUser10->setPassword("12345");
        $testUser10->setEmail("user10@gmail.com");
        $testUser10->setDisplayName("Test User 10");

        $this->entityManager->persist($testUser10);

        $this->entityManager->flush();
    }
}