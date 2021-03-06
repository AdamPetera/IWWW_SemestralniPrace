<?php


class DataTable
{
    private $dataSet;
    private $columns;

    public function __construct($dataSet) {
        $this->dataSet = $dataSet;
    }

    public function addColumn($key, $humanReadableKey) {
        $this->columns[$key] = $humanReadableKey;
    }

    public function renderTable() {
        echo '<table>';
        echo '<tr>';
        foreach ($this->columns as $key => $value) {
            echo '<th>'.$value.'</th>';
        }
        echo '</tr>';
        if (!empty($this->dataSet)) {
            foreach ($this->dataSet as $row) {
                echo '<tr>';
                foreach ($this->columns as $key => $value) {
                    echo '<td>' . $row[$key] . '</td>';
                }

                echo '</tr>';
            }
        } else {
            echo '
                <tr>
                    <td colspan="5" style="text-align: center">Žádní uživatelé k editaci :(</td>
                </tr>';
        }
        echo '</table>';
    }
}