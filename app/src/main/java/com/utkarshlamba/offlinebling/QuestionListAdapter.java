package com.utkarshlamba.offlinebling;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import java.util.ArrayList;

/**
 * Created by Ryan on 2016-01-23.
 */
public class QuestionListAdapter extends ArrayAdapter<String> {
    private final Context context;
    private ArrayList<String> questions;
    private ArrayList<String> answers;

    public QuestionListAdapter(Context context, ArrayList<String> qs, ArrayList<String> ans) {
        super(context, R.layout.custom_list_item, qs);
        this.context = context;
        questions = qs;
        answers = ans;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        LayoutInflater inflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);

        View rowView = inflater.inflate(R.layout.faq_list_item, parent, false);
        TextView questionTextView = (TextView) rowView.findViewById(R.id.faq_question_label);
        TextView answerTextView = (TextView) rowView.findViewById(R.id.faq_answer_label);
        questionTextView.setText(questions.get(position));
        answerTextView.setText(answers.get(position));


        return rowView;
    }
}
