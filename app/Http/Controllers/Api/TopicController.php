<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Services\OpenAIService;
use App\Models\ChatGptPrompt;
use App\Http\Requests\Topic\StoreRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Controllers\Controller;

class TopicController extends Controller
{
    private openAIService $openAIService;
    private string $system_prompt;

    public function __construct()
    {
        $this->openAIService = new OpenAIService();
        $this->system_prompt = config('openai.system_prompt_intro') . config('openai.system_prompt_formatting') . config('openai.system_prompt_trigger');
    }

    public function index(): JsonResponse
    {
        $topics = Topic::all();

        return response()->json($topics);
    }

    public function store(StoreRequest $request)
    {
        $chatGptPrompt = new ChatGptPrompt();

        $messages = [
            ['role' => 'system', 'content' => $this->system_prompt],
            ['role' => 'user', 'content' => 'Teach me about ' . $request->name],
        ];

        $chatGptPrompt->prompt = json_encode($messages);

        $chatGptPrompt->save();

        $apiResponse = $this->openAIService->generateText($messages);

        $modelResponse = $this->handleApiResponse($chatGptPrompt, $apiResponse);

        $learningBlocks = json_decode(json_decode($modelResponse->responses()->first())->response)->learningBlocks;

        $summary = json_decode($modelResponse->responses()->first()->response)->summary;

        $topic = Topic::create(['name' => $request->name, 'description' => $summary, 'length' => 1]);

        // input each learning block into learning_blocks table
        foreach ($learningBlocks as $learningBlock) {
            $topic->learningBlocks()->create([
                'title' => $learningBlock->title,
                'content' => $learningBlock->content,
                'order' => $learningBlock->order ?? 0,
            ]);
        }

        return response()->json($topic, 201);
    }

    public function show(string $id)
    {
        $topic = Topic::with('learningBlocks')->findOrFail($id);

        return response()->json($topic);
    }

    private function handleApiResponse(ChatGptPrompt $chatGptPrompt, $response)
    {
        if ($response->successful()) {
            $responseData = $response->json();
            $choice = $responseData['choices'][0];

            $chatGptPrompt->responses()->create([
                'response' => $choice['message']['content'],
                'response_id' => $responseData['id'],
                'object' => $responseData['object'],
                'created' => $responseData['created'],
                'model' => $responseData['model'],
                'prompt_tokens' => $responseData['usage']['prompt_tokens'],
                'completion_tokens' => $responseData['usage']['completion_tokens'],
                'total_tokens' => $responseData['usage']['total_tokens'],
                'finish_reason' => $choice['finish_reason'],
            ]);

            return $chatGptPrompt;
        } else {
            return 'FAIL';
        }
    }
}
