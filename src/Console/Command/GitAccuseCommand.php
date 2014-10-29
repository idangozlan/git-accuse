<?php namespace MattyRad\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GitAccuseCommand extends Command
{
    const GIT_BLAME_COMMAND = 'git blame -L %s,%s %s --show-email';

    protected function configure()
    {
        $this->setName('git:accuse')
            ->setDescription('Accuse someone of their bad code and email them to make them feel bad too')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'Name of the file the offending code is in'
            )
            ->addArgument(
               'linenum',
               InputArgument::REQUIRED,
               'The line number of the offending code'
           )
           ->addOption(
               'message',
               null,
               InputArgument::OPTIONAL,
               'An optional custom message to send to the offending committer'
           );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $linenum = $input->getArgument('linenum');
        $message = $input->getOption('message');

        $command = sprintf(self::GIT_BLAME_COMMAND, $linenum, $linenum, $filename);

        $text = exec($command);

        $author = $this->parseForAuthor($text);

        if (! $message) {
            $messages = include(__DIR__ . '/../../witty-shit-array.php');
            $message_id = array_rand($messages);
            $message = $messages[$message_id];
        }

        $this->sendMail($message, $author, $filename, $linenum);
    }

    private function parseForAuthor($line)
    {
        preg_match('/\<(.*)\>/', $line, $matches);
        return $matches[1];
    }

    private function sendMail($message, $author, $filename, $linenum)
    {
        $subject = $message;
        $message .= "\r\n\r\nFile: " . $filename . ':' . $linenum;

        $author = 'mradford@noip.com';
        mail ( $author , $subject , $message);
    }
}
