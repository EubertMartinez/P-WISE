<?php

class SuggestionDataRepository extends ReposittoryHelper {
    private SuggestionData $data;

    public function __construct() {
        $this->data = new SuggestionData();
    }

    public function NextCycleSuggestion($parentId, $numberOfCycle, $is_customized): array
    {
        return $this->data->NextCycleSuggestion($parentId, $numberOfCycle, $is_customized);
    }

    public function CheckTotalNumberOfCyclesWithExpenses($parentId): array
    {
        $result = $this->data->CheckTotalNumberOfCyclesWithExpenses($parentId);
        return $this->GetDataAsArray($result);
    }



    public function isCustomizedCyclesOn($parentId): array
    {
        return $this->data->isCustomizedCyclesOn($parentId);
    }
}
