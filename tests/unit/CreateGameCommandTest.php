<?php
namespace MiniGameApp\Test;

use Faker\Factory;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGameApp\Command\CreateGameCommand;
use RemiSan\Context\Context;

class CreateGameCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var PlayerId */
    private $playerId;

    /** @var MiniGameId */
    private $gameId;

    /** @var GameOptions */
    private $gameOptions;

    /** @var Context */
    private $context;

    public function setUp()
    {
        $faker = Factory::create();

        $this->playerId = PlayerId::create($faker->uuid);
        $this->gameId = MiniGameId::create($faker->uuid);
        $this->gameOptions = \Mockery::mock(GameOptions::class);
        $this->context = \Mockery::mock(Context::class);
    }

    /**
     * @test
     */
    public function itShouldBuildTheCommand()
    {
        $command = CreateGameCommand::create($this->gameId, $this->playerId, $this->gameOptions, $this->context);

        $this->assertEquals($this->gameId, $command->getGameId());
        $this->assertEquals($this->playerId, $command->getPlayerId());
        $this->assertEquals($this->gameOptions, $command->getOptions());
        $this->assertEquals($this->context, $command->getContext());
        $this->assertEquals(CreateGameCommand::NAME, $command->getCommandName());
    }
}
