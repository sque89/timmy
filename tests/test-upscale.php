<?php

/**
 * Class TestUpscale
 */
class TestUpscale extends TimmyUnitTestCase {
	/**
	 * Tests whether upscaled image file is not created.
	 */
	public function test_timmy_do_not_upscale_images() {
		$attachment = $this->create_image( [
			'file' => 'test-200px.jpg',
		] );

		image_downsize( $attachment->ID, 'large' );
		$file_path = $this->get_upload_path() . '/test-200px-1400x0-c-default.jpg';

		$this->assertFileNotExists( $file_path );
	}

	/**
	 * Tests whether upscaled image files are created when allowed.
	 */
	public function test_timmy_upscale_images_when_allowed() {
		add_filter( 'timmy/upscale', '__return_true' );

		$attachment = $this->create_image( [
			'file' => 'test-200px.jpg',
		] );

		image_downsize( $attachment->ID, 'large' );
		$file_path = $this->get_upload_path() . '/test-200px-1400x0-c-default.jpg';

		remove_filter( 'timmy/upscale', '__return_true' );

		$this->assertFileExists( $file_path );
	}

	/**
	 * Tests whether upscaled srcset sizes are not created.
	 */
	public function test_timmy_do_not_generate_srcset_sizes() {
		add_filter( 'timmy/generate_srcset_sizes', '__return_true' );

		$attachment = $this->create_image( [
			'file' => 'test-200px.jpg',
		] );

		image_downsize( $attachment->ID, 'large' );
		$file_path = $this->get_upload_path() . '/test-200px-560x0-c-default.jpg';

		remove_filter( 'timmy/generate_srcset_sizes', '__return_true' );

		$this->assertFileNotExists( $file_path );
	}

	/**
	 * Tests whether upscaled srcset image files are created when allowed.
	 */
	public function test_timmy_upscale_srcset_images_when_allowed() {
		add_filter( 'timmy/upscale', '__return_true' );
		add_filter( 'timmy/generate_srcset_sizes', '__return_true' );

		$attachment = $this->create_image( [
			'file' => 'test-200px.jpg',
		] );

		image_downsize( $attachment->ID, 'large' );
		$file_path = $this->get_upload_path() . '/test-200px-560x0-c-default.jpg';

		remove_filter( 'timmy/upscale', '__return_true' );
		remove_filter( 'timmy/generate_srcset_sizes', '__return_true' );

		$this->assertFileExists( $file_path );
	}

	public function test_oversize_default_allow_false() {
		$attachment = $this->create_image( [
			'file' => 'test-200px.jpg',
		] );
		$result     = get_timber_image_responsive( $attachment, 'oversize-default' );

		$expected = ' srcset="' . $this->get_upload_url() . '/test-200px-150x0-c-default.jpg 150w, ' . $this->get_upload_url() . '/test-200px-200x0-c-default.jpg 200w" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="width:200px;" alt=""';

		$this->assertEquals( $expected, $result );
	}

	public function test_oversize_default_allow_false_portrait_image() {
		$attachment = $this->create_image( [
			'file' => 'test-133px-portrait.jpg',
		] );
		$result     = get_timber_image_responsive( $attachment, 'oversize-default-portrait' );

		$expected = ' srcset="' . $this->get_upload_url() . '/test-133px-portrait-0x150-c-default.jpg 100w, ' . $this->get_upload_url() . '/test-133px-portrait-133x200-c-default.jpg 133w" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" style="height:200px;" alt=""';

		$this->assertEquals( $expected, $result );
	}

	public function test_oversize_default_allow_false_style_attr_false() {
		$attachment = $this->create_image( [
			'file' => 'test-200px.jpg',
		] );
		$result     = get_timber_image_responsive( $attachment, 'oversize-allow-true-style-attr-false' );

		$expected = ' srcset="' . $this->get_upload_url() . '/test-200px-150x0-c-default.jpg 150w, ' . $this->get_upload_url() . '/test-200px-200x0-c-default.jpg 200w" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt=""';

		$this->assertEquals( $expected, $result );
	}

	public function test_oversize_allow_true() {
		$attachment = $this->create_image( [
			'file' => 'test-200px.jpg',
		] );
		$result = get_timber_image_responsive( $attachment, 'oversize-allow-true' );

		$expected = ' srcset="' . $this->get_upload_url() . '/test-200px-150x0-c-default.jpg 150w, ' . $this->get_upload_url() . '/test-200px-1400x0-c-default.jpg 1400w" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt=""';

		$this->assertEquals( $expected, $result );
	}
}
