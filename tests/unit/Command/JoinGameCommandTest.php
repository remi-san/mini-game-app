<?php
namespace MiniGameApp\Test\Command;

use Faker\Factory;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\PlayerOptions;
use MiniGameApp\Command\JoinGameCommand;
use RemiSan\Context\Context;

class JoinGameCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var PlayerId */
    private $playerId;

    /** @var MiniGameId */
    private $gameId;

    /** @var PlayerOptions */
    private $playerOptions;

    /** @var Context */
    private $context;

    public function setUp()
    {
        $faker = Factory::create();

        $this->playerId = PlayerId::create($faker->uuid);
        $this->gameId = MiniGameId::create($faker->uuid);
        $this->playerOptions = \Mockery::mock(PlayerOptions::class);
        $this->context = \Mockery::mock(Context::class);
    }

    /**
     * @test
     */
    public function itShouldBuildTheCommand()
    {
        $command = JoinGameCommand::create($this->gameId, $this->playerId, $this->playerOptions, $this->context);

        $this->assertEquals($this->playerId, $command->getPlayerId());
        $this->assertEquals($this->gameId, $command->getGameId());
        $this->assertEquals($this->playerOptions, $command->getPlayerOptions());
        $this->assertEquals($this->context, $command->getContext());
        $this->assertEquals(JoinGameCommand::NAME, $command->getCommandName());
    }
}
