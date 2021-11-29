<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\CommandHistoryResource;

use App\Http\Requests\CommandHistories\RedoCommandRequest;
use App\Http\Requests\CommandHistories\ExecuteCommandRequest;
use App\Http\Requests\CommandHistories\PopulateCommandHistoriesRequest;

use App\Repositories\RabbitMQRepository;
use App\Repositories\CommandHistoryRepository;

use App\Models\CommandHistory;

use App\Jobs\RabbitMQExecuteCommandJob;

class CommandHistoryController extends Controller
{
    protected $rabbitmq;
    protected $commandHistory;

    public function __construct(
        RabbitMQRepository $rabbitMQRepository,
    	CommandHistoryRepository $commandHistoryRepository
    )
    {
        $this->rabbitmq = $rabbitMQRepository;
    	$this->commandHistory = $commandHistoryRepository;
    }

    public function index()
    {
    	return view(
    		'dashboard.command_histories.index',
    	);
    }

    public function insertCommand()
    {
        return view(
            'dashboard.command_histories.insert-command'
        );
    }

    public function populate(PopulateCommandHistoriesRequest $request)
    {
        if ($request->get('start'))
            $this->commandHistory->setStart(
                $request->get('start')
            );

        if ($request->get('end'))
            $this->commandHistory->setEnd(
                $request->get('end')
            );
        
        $commandHistories = CommandHistoryResource::collection(
            $this->commandHistory->mutations()
        );

        return response()->json([
            'commandHistories' => $commandHistories
        ]);
    }

    public function redoCommand(RedoCommandRequest $request)
    {
        $this->commandHistory->find($request->input('id'));
        $history = $this->commandHistory->redo();

        return response()->json([
            'commandHistories' => CommandHistoryResource::collection(
                collect([$history])
            ),
        ]);
    }

    public function loadCommands()
    {
        return view('dashboard.command_histories.load');
    }

    public function executeCommands(ExecuteCommandRequest $request)
    {
        $queue = $request->input('queue');
        $commands = json_decode(
            $request->input('commands'), 
            true
        );

        $this->rabbitmq->connect();
        $this->rabbitmq->selectQueue($queue);
        for ($index = 0; $index < count($commands); $index++) {
            if (! $command = $commands[$index]) 
                continue;

            // Execute commands
            $this->rabbitmq->setMessage($command);
            $this->rabbitmq->publish();

            $this->commandHistory->save([
                'executor_id' => $request->user()->id,
                'queue_name' => $queue,
                'content' => $command,
                'executed_from' => 'dashboard',
                'executed_at' => carbon()->now(),
                'execution_errors' => $this->rabbitmq->errors,
            ]);
            $this->commandHistory->setModel(new CommandHistory);
        }
        $this->rabbitmq->closeConnection();

        return response()->json([
            'commands' => $commands,
            'rabbitmq' => $this->rabbitmq->status,
        ]);
    }
}
