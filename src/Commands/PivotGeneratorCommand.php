<?php namespace Vsch\Generators\Commands;

use Symfony\Component\Console\Input\InputArgument;

class PivotGeneratorCommand extends BaseGeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:pivot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a pivot table';

    protected function getPath()
    {
        // not used
        return null;
    }

    public
    function fire()
    {
        $this->generator->setOptions($this->option());
        $tables = $this->sortDesiredTables();

        $this->call(
            'generate:migration',
            array_merge(parent::commonOptions(array()),
                array(
                    'name' => "pivot_{$tables[0]}_{$tables[1]}_table",
                    '--fields' => implode(', ', array(
                        "{$tables[0]}_id:integer:unsigned:index",
                        "{$tables[1]}_id:integer:unsigned:index",
                        "{$tables[0]}_id:foreign:references('id'):on('" . str_plural($tables[0]) . "'):onDelete('cascade')",
                        "{$tables[1]}_id:foreign:references('id'):on('" . str_plural($tables[1]) . "'):onDelete('cascade')"
                    ))
                ))
        );
    }

    public
    function sortDesiredTables()
    {
        $tableOne = str_singular($this->argument('tableOne'));
        $tableTwo = str_singular($this->argument('tableTwo'));

        $tables = array($tableOne, $tableTwo);
        sort($tables);

        return $tables;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected
    function getArguments()
    {
        return $this->mergeOptions(array(
            array('tableOne', InputArgument::REQUIRED, 'Name of the first table.'),
            array('tableTwo', InputArgument::REQUIRED, 'Name of the second table.')
        ));
    }
}

