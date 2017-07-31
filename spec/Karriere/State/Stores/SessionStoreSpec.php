<?php

namespace spec\Karriere\State\Stores;

use Illuminate\Session\Store as Session;
use Karriere\State\State;
use Karriere\State\Stores\SessionStore;
use PhpSpec\ObjectBehavior;

class SessionStoreSpec extends ObjectBehavior
{
    public function let(Session $session)
    {
        $this->beConstructedWith('prefix', $session);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SessionStore::class);
    }

    public function it_should_return_empty_state_if_no_item_is_in_session(Session $session)
    {
        $session->has('prefix/id')->willReturn(false);
        $session->get('prefix/id')->shouldNotBeCalled();

        $response = $this->get('id');

        $response->shouldHaveType(State::class);
        $response->isEmpty()->shouldReturn(true);
    }

    public function it_should_return_state_if_item_exists_in_session(Session $session)
    {
        $session->has('prefix/id')->willReturn(true);
        $session->get('prefix/id')->willReturn(['name' => 'name', 'data' => [1, 2, 3]])->shouldBeCalled();
        $session->forget('prefix/id')->shouldBeCalled();

        $response = $this->get('id');

        $response->shouldHaveType(State::class);
        $response->isEmpty()->shouldReturn(false);
        $response->name()->shouldReturn('name');
        $rawArray = $response->raw();

        $rawArray->shouldBeArray();
        $rawArray->shouldHaveCount(3);
    }

    public function it_should_store_state_in_session(Session $session)
    {
        $session->put('prefix/id', ['name' => 'name', 'data' => [1, 2, 3]])->shouldBeCalled();

        $state = new State('id', 'name', [1, 2, 3]);
        $this->put($state);
    }

    public function it_should_not_remove_state_from_session(Session $session)
    {
        $session->has('prefix/id')->willReturn(true);
        $session->get('prefix/id')->willReturn([])->shouldBeCalled();
        $session->remove('prefix/id')->shouldNotBeCalled();

        $this->get('id', true);
    }
}
