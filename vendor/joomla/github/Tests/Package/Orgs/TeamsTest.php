<?php
/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Orgs\Teams;
use Joomla\Github\Tests\Stub\GitHubTestCase;

/**
 * Test class for Teams.
 *
 * @since  1.0
 */
class TeamsTest extends GitHubTestCase
{
	/**
	 * @var Teams
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @since   1.0
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Teams($this->options, $this->client);
	}

	/**
	 * Tests the getList method
	 *
	 * @return  void
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/orgs/joomla/teams')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList('joomla'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the get method
	 *
	 * @return  void
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get(123),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the create method
	 *
	 * @return  void
	 */
	public function testCreate()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post')
			->with('/orgs/joomla/teams')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla', 'TheTeam', array('joomla-platform'), 'admin'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the createWrongPermission method
	 *
	 * @return  void
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testCreateWrongPermission()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$this->object->create('joomla', 'TheTeam', array('joomla-platform'), 'invalid');
	}

	/**
	 * Tests the edit method
	 *
	 * @return  void
	 */
	public function testEdit()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('patch')
			->with('/teams/123')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit(123, 'TheTeam', 'admin'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the editWrongPermission method
	 *
	 * @return  void
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testEditWrongPermission()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->object->edit(123, 'TheTeam', 'invalid');
	}

	/**
	 * Tests the delete method
	 *
	 * @return  void
	 */
	public function testDelete()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/teams/123')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->delete(123),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListMembers method
	 *
	 * @return  void
	 */
	public function testGetListMembers()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123/members')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListMembers(123),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the isMember method
	 *
	 * @return  void
	 */
	public function testIsMember()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123/members/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->isMember(123, 'elkuku'),
			$this->equalTo(json_decode(true))
		);
	}

	/**
	 * Tests the isMemberNo method
	 *
	 * @return  void
	 */
	public function testIsMemberNo()
	{
		$this->response->code = 404;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123/members/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->isMember(123, 'elkuku'),
			$this->equalTo(json_decode(false))
		);
	}

	/**
	 * Tests the isMemberUnexpected method
	 *
	 * @return  void
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testIsMemberUnexpected()
	{
		$this->response->code = 666;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123/members/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->isMember(123, 'elkuku'),
			$this->equalTo(json_decode(true))
		);
	}

	/**
	 * Tests the addMember method
	 *
	 * @return  void
	 */
	public function testAddMember()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('put')
			->with('/teams/123/members/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->addMember(123, 'elkuku'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the removeMember method
	 *
	 * @return  void
	 */
	public function testRemoveMember()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/teams/123/members/elkuku')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->removeMember(123, 'elkuku'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getListRepos method
	 *
	 * @return  void
	 */
	public function testGetListRepos()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123/repos')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getListRepos(123),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the checkRepo method
	 *
	 * @return  void
	 */
	public function testCheckRepo()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123/repos/joomla/cms')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->checkRepo(123, 'joomla', 'cms'),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the checkRepoNo method
	 *
	 * @return  void
	 */
	public function testCheckRepoNo()
	{
		$this->response->code = 404;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123/repos/joomla/cms')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->checkRepo(123, 'joomla', 'cms'),
			$this->equalTo(false)
		);
	}

	/**
	 * Tests the checkRepoUnexpected method
	 *
	 * @return  void
	 *
	 * @expectedException \UnexpectedValueException
	 */
	public function testCheckRepoUnexpected()
	{
		$this->response->code = 666;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/teams/123/repos/joomla/cms')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->checkRepo(123, 'joomla', 'cms'),
			$this->equalTo(true)
		);
	}

	/**
	 * Tests the addRepo method
	 *
	 * @return  void
	 */
	public function testAddRepo()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('put')
			->with('/teams/123/repos/joomla/joomla-platform')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->addRepo(123, 'joomla', 'joomla-platform'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the removeRepo method
	 *
	 * @return  void
	 */
	public function testRemoveRepo()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/teams/123/repos/joomla/joomla-platform')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->removeRepo(123, 'joomla', 'joomla-platform'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}
}
