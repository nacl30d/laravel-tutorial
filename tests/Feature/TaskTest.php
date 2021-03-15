<?php

namespace Tests\Feature;

use App\Http\Requests\CreateTask;
use Carbon\Carbon;
use Database\Seeders\FolderTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{

    // テストケースごとにDBをリフレッシュしてmigrationを再実行
    use RefreshDatabase;
    /**
     * 各テストメソッドの実行前に呼ばれる
     *
     * @return void
     */
    public function setU()
    {
        parent::setUp();

        // テストケース実行前にフォルダデータを作成する
        $this->seed(FolderTableSeeder::class);
    }

    /**
     * 期限日が日付ではない場合はバリデーションエラー
     * @test
     */
    public function due_date_should_be_date() {
        $response = $this->post('/folders/1/tasks/create', [
            'title' => 'Sample task',
            'due_date' => 123, // Invalid value
        ]);

        $response->assertSessionHasErrors([
            'due_date' => '期限日 には日付を入力してください。',
        ]);
    }

    /**
     * 期限日が過去日付の場合はバリデーションエラー
     * @test
     */
    public function due_date_should_not_be_past() {
        $response = $this->post('/folders/1/tasks/create', [
            'title' => 'Sample task' ,
            'due_date' => Carbon::yesterday()->format('Y/m/d'), //Invalid value
        ]);

        $response->assertSessionHasErrors([
            'due_date' => '期限日 には今日以降の日付を入力してください。',
        ]);
    }
}
