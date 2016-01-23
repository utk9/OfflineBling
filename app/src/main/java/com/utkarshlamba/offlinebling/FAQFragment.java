package com.utkarshlamba.offlinebling;

import android.app.Fragment;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListAdapter;
import android.widget.ListView;

import java.util.ArrayList;

/**
 * Created by utk on 16-01-23.
 */
public class FAQFragment extends Fragment {

    static  ArrayList<String> questionsList;
    static ArrayList<String> answersList;
    static QuestionListAdapter adapter;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        return inflater.inflate(R.layout.layout_faq_fragment, container, false);
    }

    @Override
    public void onActivityCreated(Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        questionsList = new ArrayList<>();
        answersList = new ArrayList<>();
        questionsList.add("what is your name");
        answersList.add("john");
        questionsList.add("what is your jam");
        answersList.add("sugar strawberry");

        ListView listView = (ListView) getActivity().findViewById(R.id.faq_listView);
        adapter = new QuestionListAdapter(getActivity(),
                questionsList, answersList);
        listView.setAdapter(adapter);

        //new FetchDataFromDBTask().execute();



    }


}
