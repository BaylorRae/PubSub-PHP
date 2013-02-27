<?php

require dirname(__FILE__) . '/../../lib/pub_sub/pub_sub.php';

use PubSub\PubSub;

class PubSubTest extends PHPUnit_Framework_TestCase {
  
  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function it_should_throw_exception_when_invalid() {
    PubSub::subscribe('/add/user', 'lolnofunctionhere');
  }
  
  /**
   * @test
   */
  public function it_should_add_new_event() {
    PubSub::subscribe('/add/user', function() {});
    $events = PubSub::events();
    
    $this->assertArrayHasKey('/add/user', $events);
    $this->assertCount(1, $events['/add/user']);
  }
  
  /**
   * @test
   */
  public function it_should_call_the_event_callback() {
    $mock = $this->getMock('stdClass', array('foo'));
    $mock->expects($this->once())
         ->method('foo')
         ->with('foobar');
             
    PubSub::subscribe('/do/something', array($mock, 'foo'));
    PubSub::publish('/do/something', 'foobar');
  }
  
  /**
   * @test
   */
  public function it_should_call_multiple_event_callbacks() {
    $mock = $this->getMock('stdClass', array('foo', 'bar'));
    
    $mock->expects($this->once())
         ->method('foo')
         ->with('baz');
         
    
    $mock->expects($this->once())
         ->method('bar')
         ->with('baz');
         
    PubSub::subscribe('/a/thing', array($mock, 'foo'));
    PubSub::subscribe('/a/thing', array($mock, 'bar'));
    
    PubSub::publish('/a/thing', 'baz');
  }
  
  /**
   * @test
   */
  public function it_clears_events_when_unsubscribed() {
    $mock = $this->getMock('stdClass', array('foo'));
    PubSub::subscribe('/what/up', array($mock, 'foo'));
    
    $events = PubSub::events();
    $this->assertTrue(!empty($events['/what/up']));
    
    PubSub::unsubscribe('/what/up');
    $events = PubSub::events();
    $this->assertEmpty($events['/what/up']);
  }
  
  /**
   * @test
   */
  public function it_unsubscribes_all_events() {
    $mock = $this->getMock('stdClass', array('foo'));
    PubSub::subscribe('/it/here', array($mock, 'foo'));
    $this->assertGreaterThan(0, count(PubSub::events()));
    
    PubSub::unsubscribe_all();
    $this->assertEquals(0, count(PubSub::events()));
  }
  
}