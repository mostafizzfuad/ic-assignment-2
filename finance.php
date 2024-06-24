<?php

class FinanceApp {
    private $incomeFile = 'incomes.json';
    private $expenseFile = 'expenses.json';
    private $categoryFile = 'categories.json';

    public function __construct() {
        $this->initializeFiles();
    }

    private function initializeFiles() {
        if (!file_exists($this->incomeFile)) {
            file_put_contents($this->incomeFile, json_encode([]));
        }
        if (!file_exists($this->expenseFile)) {
            file_put_contents($this->expenseFile, json_encode([]));
        }
        if (!file_exists($this->categoryFile)) {
            file_put_contents($this->categoryFile, json_encode(['Income' => [], 'Expense' => []]));
        }
    }

    public function run() {
        while (true) {
            echo "1. Add income\n";
            echo "2. Add expense\n";
            echo "3. View incomes\n";
            echo "4. View expenses\n";
            echo "5. View savings\n";
            echo "6. View categories\n";
            echo "7. Exit\n";
            echo "Enter your option: ";
            $option = trim(fgets(STDIN));
            switch ($option) {
                case '1':
                    $this->addIncome();
                    break;
                case '2':
                    $this->addExpense();
                    break;
                case '3':
                    $this->viewIncomes();
                    break;
                case '4':
                    $this->viewExpenses();
                    break;
                case '5':
                    $this->viewSavings();
                    break;
                case '6':
                    $this->viewCategories();
                    break;
                case '7':
                    exit("Goodbye!\n");
                default:
                    echo "Invalid option. Please try again.\n";
            }
        }
    }

    private function addIncome() {
        echo "Enter income description: ";
        $description = trim(fgets(STDIN));
        echo "Enter income amount: ";
        $amount = trim(fgets(STDIN));
        echo "Enter income category: ";
        $category = trim(fgets(STDIN));

        $incomes = json_decode(file_get_contents($this->incomeFile), true);
        $categories = json_decode(file_get_contents($this->categoryFile), true);
        if (!in_array($category, $categories['Income'])) {
            $categories['Income'][] = $category;
            file_put_contents($this->categoryFile, json_encode($categories));
        }

        $incomes[] = ['description' => $description, 'amount' => $amount, 'category' => $category];
        file_put_contents($this->incomeFile, json_encode($incomes));
        echo "Income added successfully.\n";
    }

    private function addExpense() {
        echo "Enter expense description: ";
        $description = trim(fgets(STDIN));
        echo "Enter expense amount: ";
        $amount = trim(fgets(STDIN));
        echo "Enter expense category: ";
        $category = trim(fgets(STDIN));

        $expenses = json_decode(file_get_contents($this->expenseFile), true);
        $categories = json_decode(file_get_contents($this->categoryFile), true);
        if (!in_array($category, $categories['Expense'])) {
            $categories['Expense'][] = $category;
            file_put_contents($this->categoryFile, json_encode($categories));
        }

        $expenses[] = ['description' => $description, 'amount' => $amount, 'category' => $category];
        file_put_contents($this->expenseFile, json_encode($expenses));
        echo "Expense added successfully.\n";
    }

    private function viewIncomes() {
        $incomes = json_decode(file_get_contents($this->incomeFile), true);
        if (empty($incomes)) {
            echo "No incomes recorded.\n";
        } else {
            foreach ($incomes as $income) {
                echo "Description: {$income['description']}, Amount: {$income['amount']}, Category: {$income['category']}\n";
            }
        }
    }

    private function viewExpenses() {
        $expenses = json_decode(file_get_contents($this->expenseFile), true);
        if (empty($expenses)) {
            echo "No expenses recorded.\n";
        } else {
            foreach ($expenses as $expense) {
                echo "Description: {$expense['description']}, Amount: {$expense['amount']}, Category: {$expense['category']}\n";
            }
        }
    }

    private function viewSavings() {
        $incomes = json_decode(file_get_contents($this->incomeFile), true);
        $expenses = json_decode(file_get_contents($this->expenseFile), true);

        $totalIncome = array_reduce($incomes, function($carry, $item) {
            return $carry + $item['amount'];
        }, 0);

        $totalExpense = array_reduce($expenses, function($carry, $item) {
            return $carry + $item['amount'];
        }, 0);

        $savings = $totalIncome - $totalExpense;
        echo "Total savings: $savings\n";
    }

    private function viewCategories() {
        $categories = json_decode(file_get_contents($this->categoryFile), true);
        echo "Income Categories:\n";
        foreach ($categories['Income'] as $category) {
            echo "- $category\n";
        }
        echo "Expense Categories:\n";
        foreach ($categories['Expense'] as $category) {
            echo "- $category\n";
        }
    }
}

$app = new FinanceApp();
$app->run();

?>
